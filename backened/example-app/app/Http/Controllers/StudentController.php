<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use App\Helpers\Helper;
use App\Http\Requests\SubmitFormRequest;
use App\Http\Requests\UploadQuizVideoRequest;
use App\Http\Requests\AttemptQuizRequest;
use App\DTO\SubmitFormDTO;
use App\DTO\UploadQuizVideoDTO;
use App\DTO\AttemptQuizDTO;
use App\DTO\GetQuizQuestionsDTO;
use App\Http\Requests\GetQuizQuestionsRequest;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected $studentService;
    protected $quizService;

    public function __construct(StudentService $studentService ,QuizService $quizService)
    {
        $this->studentService = $studentService;
        $this->quizService = $quizService;

    }
    public function getQuizQuestions($quizId)
    {
        $user = auth()->user();
        
        if (!$user) {
            return Helper::error('User is not authenticated', 401);
        }
    
        $quiz = $this->studentService->getQuizQuestions($user, $quizId);
    
        if (!$quiz) {
            return Helper::error('Quiz is either not available or has expired', 403);
        }
    
        return Helper::success($quiz, 'Quiz questions retrieved successfully');
    }
    public function attemptQuiz(AttemptQuizRequest $request, $quizId)
    {
        $user = auth()->user();
        $dto = AttemptQuizDTO::fromRequest($request->validated());
    
        // Handle video upload if present
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('quiz_videos', 'public');
            $dto->videoPath = $videoPath;  // Update the DTO with the video path
        }
    
        $result = $this->studentService->attemptQuiz($user, $quizId, $dto);
    
        if ($result === null) {
            return Helper::error('Quiz already attempted.', 422);
        }
    
        return Helper::success($result, 'Quiz submitted successfully');
    }
    

    public function getQuizResults($quizId)
    {
        $user = auth()->user();
        $results = $this->studentService->getQuizResults($user, $quizId);

        if (!$results) {
            return Helper::error('Quiz results not found', 404);
        }

        return Helper::success($results, 'Quiz results retrieved successfully');
    }

    public function submitForm(SubmitFormRequest $request)
    {
        $cvPath = $request->file('cv_file')->storeAs('cv_files', time() . '_' . $request->file('cv_file')->getClientOriginalName(), 'public');
        $dto = SubmitFormDTO::fromRequest($request->validated(), $cvPath);
        $submission = $this->studentService->submitForm($dto);

        return Helper::success($submission, 'Form submitted successfully');
    }

    public function uploadQuizVideo(UploadQuizVideoRequest $request, $quizId)
{
    $user = auth()->user();
    
    // Store the video in a secure directory and generate a unique filename
    $videoPath = $request->file('video')->storeAs(
        'quiz_videos/' . $quizId . '/' . $user->id,
        time() . '_' . $request->file('video')->getClientOriginalName(),
        'public'
    );
    
    // Use DTO to create a new record in the DB
    $dto = UploadQuizVideoDTO::fromFilePath($videoPath);
    $quizAttempt = $this->studentService->uploadQuizVideo($user, $quizId, $dto);

    return Helper::success($quizAttempt, 'Video uploaded successfully');
}

     // Fetch quiz questions for students (with date validation)
     public function getQuizDetailsForStudent(GetQuizQuestionsRequest $request, $quizId)
     {
         // Convert request to DTO
         $dto = GetQuizQuestionsDTO::fromRequest(['quizId' => $quizId]);
 
         // Fetch quiz questions via service (forAdmin = false)
         $questions = $this->quizService->getQuizQuestions($dto);
 
         if (!$questions) {
             return Helper::error('Quiz is not available', 403);  // Forbidden if quiz is not accessible
         }
 
         // Return the questions as a response
         return Helper::success($questions, 'Quiz questions fetched successfully');
     }
        // Start the quiz
    public function startQuiz($quizId)
    {
        $user = auth()->user();

        // Update quiz status to active
        $quiz = $this->quizService->startQuiz($quizId);

        return Helper::success($quiz, 'Quiz started successfully');
    }


    // Finish the quiz
    public function finishQuiz($quizId)
    {
        $user = auth()->user();

        // Update quiz status to done
        $quiz = $this->quizService->finishQuiz($quizId);

        return Helper::success($quiz, 'Quiz finished successfully');
    }

 // Method to get the quizzes assigned to the student
 public function getStudentQuizzes(Request $request)
 {
     try {
         // Fetch the current authenticated student ID
         $studentId = Auth::id(); // Assumes you're using Laravel Auth

         // Use the QuizService to fetch the assigned and attempted quizzes
         $quizzes = $this->quizService->getQuizzesForStudent($studentId);

         return response()->json([
             'success' => true,
             'data' => $quizzes,
         ], 200);

     } catch (\Exception $e) {
         return response()->json([
             'success' => false,
             'message' => 'Failed to fetch quizzes: ' . $e->getMessage(),
         ], 500);
     }
 }

    
}
