<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Services\AdminService;
use App\Helpers\Helper;
use App\Http\Requests\AddManagerRequest;
use App\DTO\AddManagerDTO;
use App\DTO\AddQuestionDTO;
use App\DTO\ApproveStudentDTO;
use App\DTO\RejectStudentDTO;
use App\DTO\AssignQuizDTO;
use App\Http\Requests\AssignQuizRequest;
use App\Http\Requests\AddStudentRequest;
use App\Services\QuizService;  // Import QuizService

use App\DTO\AddStudentDTO;
use App\DTO\AddSupervisorDTO;
use App\DTO\AssignPermissionDTO;
use App\DTO\GetQuizQuestionsDTO;
use App\DTO\StudentResultWithVideoDTO;
use App\DTO\UpdateStudentDTO;
use App\Http\Requests\AddQuestionRequest;
use App\Http\Requests\AddSupervisorRequest;
use App\Http\Requests\AssignPermissionRequest;
use App\Http\Requests\GetQuizQuestionsRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;
    protected $quizService;  // Add QuizService property

    public function __construct(AdminService $adminService ,QuizService $quizService)
    {
        $this->adminService = $adminService;
        $this->quizService = $quizService;

    }

    public function addManager(AddManagerRequest $request)
    {
        $dto = AddManagerDTO::fromRequest($request->validated());
        $manager = $this->adminService->addManager($dto);
        return Helper::success($manager, 'Manager added successfully');
    }

    public function approveStudent($id)
    {
        $dto = ApproveStudentDTO::fromId($id);
        $student = $this->adminService->approveStudent($dto);
        return Helper::success($student, 'Student approved successfully');
    }

    public function rejectStudent($id)
    {
        $dto = RejectStudentDTO::fromId($id);
        $student = $this->adminService->rejectStudent($dto);
        return Helper::success($student, 'Student rejected successfully');
    }

    public function addQuestion($quizId, AddQuestionRequest $request)
    {
        // Convert request to DTO
        $dto = AddQuestionDTO::fromRequest($request->validated());

        // Call service to handle adding a question
        $question = $this->adminService->addQuestionToQuiz($quizId, $dto);

        return Helper::success($question, 'Question added successfully');
    }

    // Merged getQuizDetailsForStudent method from QuizController
    public function getQuizDetailsForStudent(GetQuizQuestionsRequest $request, $quizId)
    {
        // Convert request to DTO
        $dto = GetQuizQuestionsDTO::fromRequest(['quizId' => $quizId]);

        // Call service to get the quiz questions
        $questions = $this->quizService->getQuizQuestions($dto);

        // Return the questions as a response
        return Helper::success($questions, 'Quiz questions fetched successfully');
    }
    public function assignQuiz(AssignQuizRequest $request, $studentId)
    {
        try {
            $dto = AssignQuizDTO::fromRequest($request->validated());
            $student = User::findOrFail($studentId);
            $quiz = $this->adminService->assignQuiz($student, $dto);
            return Helper::success($quiz, 'Quiz assigned successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(), // Returns detailed validation errors
            ], 422);
        }
    }

    public function addStudent(AddStudentRequest $request)
    {
        $dto = AddStudentDTO::fromRequest($request->validated());
        $student = $this->adminService->addStudent($dto);
        return Helper::success($student, 'Student added successfully');
    }

    public function assignPermissionToUser(AssignPermissionRequest $request)
    {
        $dto = AssignPermissionDTO::fromRequest($request->validated());
        $this->adminService->assignPermissionToUser($dto->userId, $dto->permission);
        return Helper::success(null, 'Permission assigned successfully');
    }

    public function resendPasswordSetup($userId)
    {
        $user = User::findOrFail($userId);
        $this->adminService->resendPasswordSetup($user);
        return Helper::success(null, 'Password setup email resent successfully');
    }

    public function viewStudentSubmissions()
    {
        $submissions = $this->adminService->viewStudentSubmissions();
        return Helper::success($submissions);
    }

    public function filterStudentSubmissions(Request $request)
    {
        $status = $request->query('status', null);
        $submissions = $this->adminService->filterStudentSubmissions($status);
        return Helper::success($submissions);
    }

    public function viewQuizVideos($quizId)
{
    $videos = QuizAttempt::where('quiz_id', $quizId)
                ->whereNotNull('video_path')
                ->with('user')
                ->get();
    
    return Helper::success($videos, 'Quiz videos retrieved successfully');
}

public function viewVideo($attemptId)
{
    $attempt = QuizAttempt::findOrFail($attemptId);

    // Return the URL for the video to be played on the frontend
    return response()->json([
        'video_url' => asset('storage/' . $attempt->video_path)
    ]);
}

// Method to delete a student
public function deleteStudent($studentId)
{
    $this->adminService->deleteStudent($studentId);

    return Helper::success(null, 'Student deleted successfully');
}

// Method to update a student
public function updateStudent(UpdateStudentRequest $request, $studentId)
{
    $dto = UpdateStudentDTO::fromRequest($request->validated());
    $student = $this->adminService->updateStudent($dto, $studentId);

    return Helper::success($student, 'Student updated successfully');
}
// public function viewActiveStudents()
// {
//     // This will return only students where the 'deleted_at' column is NULL (not soft deleted)
//     $students = User::all();

//     return response()->json([
//         'success' => true,
//         'data' => $students,
//         'message' => 'Active students retrieved successfully'
//     ]);
// }

// Method to add supervisor
public function addSupervisor(AddSupervisorRequest $request)
{
    $dto = AddSupervisorDTO::fromRequest($request->validated());
    $supervisor = $this->adminService->addSupervisor($dto);
    return Helper::success($supervisor, 'Supervisor added successfully');
}
public function getManagers()
{
    // Assuming "role" is a column in the users table
    $managers = User::where('role', 'manager')->get();

    return response()->json($managers);
}







 // Fetch all active students (assuming the role for students is 'student')
 public function viewActiveStudents()
 {
     $students = User::where('role', 'student')  // Assuming 'role' is stored in the 'users' table
                     ->whereNull('deleted_at')   // Soft-deleted students won't be fetched
                     ->get();

     return response()->json([
         'success' => true,
         'data' => $students,
     ]);
 }

 // Soft delete a student
 public function softDeleteStudent($id)
 {
     $student = User::findOrFail($id);
     $student->delete();  // This will perform a soft delete

     return response()->json([
         'success' => true,
         'message' => 'Student soft deleted successfully',
     ]);
 }




 public function showStudentResultWithVideo($userId, $quizId)
{
    // Call service to get student result with video
    $result = $this->adminService->getStudentResultWithVideo($userId, $quizId);

    if (!$result) {
        return Helper::error('Quiz attempt not found', 404);
    }

    return Helper::success($result, 'Student result with video retrieved successfully');
}

}
