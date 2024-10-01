<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// Public routes (no authentication required)

// Login route for JWT authentication
Route::post('login', [AuthController::class, 'login']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Protecting all routes under JWT middleware, using 'auth:api'
Route::middleware(['auth:api'])->group(function () {

    // Logout route (JWT token invalidation)
    Route::post('logout', [AuthController::class, 'logout']);

    // Routes for Admin (restricted by 'admin' role)
    Route::middleware(['role:admin'])->group(function () {
        Route::post('admin/add-manager', [AdminController::class, 'addManager']);
        Route::post('admin/add-student', [AdminController::class, 'addStudent']);
        Route::get('admin/quiz/{quizId}/details', [QuizController::class, 'getQuizDetailsForAdmin']);
        Route::post('admin/add-supervisor', [AdminController::class, 'addSupervisor']);
        Route::get('/admin/managers', [AdminController::class, 'getManagers']);

        // Route to assign permissions directly to a user
        Route::post('/admin/assign-permission', [AdminController::class, 'assignPermissionToUser']);
        
        // View all students
        Route::get('/admin/students', [AdminController::class, 'viewStudents']);
        
        // Add questions to a quiz
        Route::post('admin/add-question/{quizId}', [AdminController::class, 'addQuestion']);

        Route::post('admin/approve-student/{id}', [AdminController::class, 'approveStudent']);
        Route::post('admin/reject-student/{id}', [AdminController::class, 'rejectStudent']);

        Route::post('admin/assign-quiz/{studentId}', [AdminController::class, 'assignQuiz']);
        Route::post('admin/resend-password/{userId}', [AdminController::class, 'resendPasswordSetup']);
        Route::get('admin/quiz/{quizId}/attempts', [AdminController::class, 'viewQuizAttempts']);
        Route::get('admin/students/filter', [AdminController::class, 'filterStudents']);
        Route::get('admin/quiz/{quizId}/videos', [AdminController::class, 'viewQuizVideos']);
        Route::get('admin/quiz-attempt/{attemptId}/video', [AdminController::class, 'viewVideo']);
        Route::delete('admin/student/{studentId}', [AdminController::class, 'deleteStudent'])->middleware('role:admin');
        Route::put('admin/student/{studentId}', [AdminController::class, 'updateStudent'])->middleware('role:admin');
        //Route::get('admin/students', [AdminController::class, 'viewActiveStudents'])->middleware('auth:api');
     
        Route::get('admin/students', [AdminController::class, 'viewActiveStudents'])->middleware('auth:api');
        
        // Soft delete a student
        Route::delete('admin/students/{id}', [AdminController::class, 'softDeleteStudent'])->middleware('auth:api');
        // In routes/api.php or web.php (depending on your setup)
        Route::get('admin/quizzes', [QuizController::class, 'getQuizzes']);
        Route::get('admin/student-result-with-video/{userId}/{quizId}', [AdminController::class, 'showStudentResultWithVideo']);

    });

    // Routes for Manager (restricted by 'manager' or 'admin' role)
    Route::middleware(['role:manager|admin'])->group(function () {
        Route::post('manager/assign-quiz/{studentId}', [AdminController::class, 'assignQuiz']);
        Route::get('manager/students/filter', [AdminController::class, 'filterStudents']);
        Route::get('admin/student-submissions/filter', [AdminController::class, 'filterStudentSubmissions']);
        Route::get('manager/students', [AdminController::class, 'viewActiveStudents'])->middleware('auth:api');
        Route::get('manager/quizzes', [QuizController::class, 'getQuizzes']);


    });

    // Routes for Student (restricted by 'student' role)
    Route::middleware(['role:student'])->group(function () {
        // Route for fetching quiz questions for students
        Route::get('student/quiz/{quizId}/questions', [QuizController::class, 'getQuizDetailsForStudent']);
        Route::get('/student/quizzes', [StudentController::class, 'getStudentQuizzes']);

        // Route for students to attempt a quiz
        Route::post('student/attempt-quiz/{quizId}', [StudentController::class, 'attemptQuiz']);

        // Route for fetching quiz results
        Route::get('student/quiz-results/{quizId}', [StudentController::class, 'getQuizResults']);


        Route::post('student/quiz/{quizId}/upload-video', [StudentController::class, 'uploadQuizVideo']);

    // Route for students to start a quiz
    Route::post('student/quiz/{quizId}/start', [StudentController::class, 'startQuiz']);

    // Route for students to finish a quiz
    Route::post('student/quiz/{quizId}/finish', [StudentController::class, 'finishQuiz']);

    // Route for students to attempt a quiz (actual answering of questions)
    Route::post('student/attempt-quiz/{quizId}', [StudentController::class, 'attemptQuiz']);
    Route::post('student/quiz/{quizId}/upload-video', [StudentController::class, 'uploadQuizVideo']);

    });


  // Routes for Supervisor (restricted by 'supervisor' role)
  Route::middleware(['role:supervisor'])->group(function () {
    

    // Supervisor can assign quizzes to students
    Route::post('supervisor/assign-quiz/{studentId}', [AdminController::class, 'assignQuiz']);

    // Supervisor can view quiz questions
    Route::get('supervisor/quiz/{quizId}/questions', [QuizController::class, 'getQuizDetailsForAdmin']);

    // Supervisor can filter students
    //Route::get('supervisor/students/filter', [AdminController::class, 'filterStudents']);
    Route::get('supervisor/student-submissions/filter', [AdminController::class, 'filterStudentSubmissions']);

    // Supervisor can edit students
    Route::put('supervisor/student/{studentId}', [AdminController::class, 'updateStudent']);

    // Supervisor can delete students
    Route::delete('supervisor/student/{studentId}', [AdminController::class, 'deleteStudent']);
});


});
Route::middleware(['auth:api', 'role:admin|manager|supervisor'])->group(function () {
    Route::get('/admin/show-students', [AdminController::class, 'viewStudentSubmissions']);
    Route::get('/manager/view-submissions', [AdminController::class, 'viewStudentSubmissions']);
    Route::get('/supervisor/view-submissions', [AdminController::class, 'viewStudentSubmissions']);
});