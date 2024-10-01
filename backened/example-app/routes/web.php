<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/reset-password/{id}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
// Route for student form submission
Route::post('/student/submit-form', [StudentController::class, 'submitForm']);

// Protected routes for admin and manager to view student submissions
Route::middleware(['auth:api', 'role:admin|manager|supervisor'])->group(function () {
    Route::get('/admin/show-students', [AdminController::class, 'viewStudentSubmissions']);
    Route::get('/manager/view-submissions', [AdminController::class, 'viewStudentSubmissions']);
    Route::get('/supervisor/view-submissions', [AdminController::class, 'viewStudentSubmissions']);
});