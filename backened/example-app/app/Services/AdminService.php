<?php

namespace App\Services;

use App\DTO\AddManagerDTO;
use App\DTO\AddQuestionDTO;
use App\DTO\ApproveStudentDTO;
use App\DTO\RejectStudentDTO;
use App\DTO\AssignQuizDTO;
use App\DTO\AddStudentDTO;
use App\DTO\AddSupervisorDTO;
use App\DTO\GetQuizQuestionsDTO;
use App\DTO\StudentResultWithVideoDTO;
use App\DTO\UpdateStudentDTO;
use App\Models\User;
use App\Models\StudentSubmission;
use App\Models\Quiz;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordSetupMail;
use App\Mail\RejectionNoticeMail;
use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminService
{
    public function addManager(AddManagerDTO $dto)
    {
        $manager = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt('password'),
            'role' => 'manager',
        ]);

        $manager->assignRole('manager');

        $token = Str::random(60);
        $manager->update(['reset_token' => $token]);

        Mail::to($manager->email)->send(new PasswordSetupMail($manager, $token));

        return $manager;
    }

    public function approveStudent(ApproveStudentDTO $dto)
    {
        $submission = StudentSubmission::findOrFail($dto->id);
        $existingStudent = User::where('email', $submission->email)->first();

        if (!$existingStudent) {
            $student = User::create([
                'name' => $submission->name,
                'email' => $submission->email,
                'password' => bcrypt('password'),
                'role' => 'student',
                'status' => 'approved',
            ]);
            $student->assignRole('student');
        } else {
            $existingStudent->update(['status' => 'approved']);
            $student = $existingStudent;
        }

        $submission->update(['status' => 'approved']);

        Mail::to($submission->email)->send(new PasswordSetupMail($student, $student->reset_token ?? null));

        return $student;
    }

    public function rejectStudent(RejectStudentDTO $dto)
    {
        $submission = StudentSubmission::findOrFail($dto->id);
        $student = User::where('email', $submission->email)->first();

        if ($student) {
            $student->update(['status' => 'rejected']);
        } else {
            $student = User::create([
                'name' => $submission->name,
                'email' => $submission->email,
                'password' => bcrypt('password'),
                'role' => 'student',
                'status' => 'rejected',
            ]);
        }

        $submission->update(['status' => 'rejected']);

        Mail::to($submission->email)->queue(new RejectionNoticeMail($student));

        return $student;
    }

    public function assignQuiz($student, AssignQuizDTO $dto)
    {
        $quiz = Quiz::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'schedule_date' => now()->addDays(2),
            'expiration_date' => now()->addDays(7),
        ]);

        $student->quizzes()->attach($quiz->id);

        return $quiz;
    }

    public function addQuestionToQuiz($quizId, AddQuestionDTO $dto)
    {
        // Find the quiz by ID
        $quiz = Quiz::findOrFail($quizId);

        // Create a new question associated with the quiz
        $question = Question::create([
            'quiz_id' => $quiz->id,
            'question_text' => $dto->questionText,
            'correct_answer' => $dto->correctAnswer,
            'options' => json_encode($dto->options),  // Storing options as a JSON string
        ]);

        return $question;
    }
    public function addStudent(AddStudentDTO $dto)
    {
        $student = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt('password'),
        ]);

        $student->assignRole('student');

        $token = Str::random(60);
        $student->update(['reset_token' => $token]);

        Mail::to($student->email)->queue(new PasswordSetupMail($student, $token));

        return $student;
    }

    public function assignPermissionToUser($userId, $permission)
    {
        $user = User::find($userId);
        $user->givePermissionTo($permission);
    }

    public function resendPasswordSetup($user)
    {
        $token = Str::random(60);
        $user->update(['reset_token' => $token]);

        Mail::to($user->email)->queue(new PasswordSetupMail($user, $token));
    }

    public function viewStudentSubmissions()
    {
        return StudentSubmission::all();
    }

    public function filterStudentSubmissions($status)
    {
        $query = StudentSubmission::query();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function getQuizQuestions(GetQuizQuestionsDTO $dto)
    {
        // Find the quiz and load its questions
        $quiz = Quiz::with('questions')->findOrFail($dto->quizId);

        // Return the quiz questions
        return $quiz->questions;
    }

       // Method to delete a student
       public function deleteStudent($studentId)
       {
           // Find the student by ID
           $student = User::findOrFail($studentId);
   
           // Delete the student
           $student->delete();
   
           return true;
       }

       // Method to update student details
       public function updateStudent(UpdateStudentDTO $dto, $studentId)
       {
           try {
               // Find the student by ID
               $student = User::findOrFail($studentId);
       
               // Update the student details
               $student->update([
                   'name' => $dto->name,
                   'email' => $dto->email,
                   'status' => $dto->status ?? $student->status,  // optional status update, keep existing status if not provided
               ]);
       
               return $student;
           } catch (\Exception $e) {
               // Log the error for debugging purposes
               Log::error('Failed to update student: ' . $e->getMessage());
       
               // Return a meaningful error message
               return response()->json([
                   'success' => false,
                   'message' => 'Failed to update student. Please try again.',
               ], 500);
           }
       }
       

    public function addSupervisor(AddSupervisorDTO $dto)
    {
        // Create the supervisor user
        $supervisor = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => bcrypt('password'), 
            'role' => 'supervisor',                   // Default password, this should be reset later
        ]);

        // Assign the supervisor role
        $supervisor->assignRole('supervisor');

        return $supervisor;
        // Generate a password setup token
        $token = Str::random(60);
        $supervisor->update(['reset_token' => $token]);

        // Send email to supervisor to set up the password
        Mail::to($supervisor->email)->queue(new PasswordSetupMail($supervisor, $token));

        return $supervisor;
    }



    public function getStudentResultWithVideo($userId, $quizId)
    {
        // Find the quiz attempt for the student
        $attempt = QuizAttempt::where('user_id', $userId)
                    ->where('quiz_id', $quizId)
                    ->first();

        if (!$attempt) {
            Log::error("Quiz attempt not found for userId: $userId and quizId: $quizId");
            return null; // Or throw an exception
        }

        // Fetch the user's name (student's name) and the quiz title
        $student = User::findOrFail($userId);
        $quiz = Quiz::findOrFail($quizId);

        // Build the DTO for the response
        return [
            'quizId' => $quizId,
            'quizTitle' => $quiz->title, // Quiz title
            'studentId' => $userId,
            'studentName' => $student->name, // Student's name
            'score' => $attempt->score,
            'videoUrl' => asset('storage/' . $attempt->video_path) // Assuming the video is stored in 'storage'
        ];
    }
   
}
