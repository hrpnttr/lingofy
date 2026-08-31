<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::get('/', [CourseController::class, 'home'])->name('home');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::post('/courses/{id}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');

Route::get('/placement-tests', [QuizController::class, 'placementTests'])->name('placement-tests');

// Student Authenticated Routes
Route::middleware([])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/my-courses', [CourseController::class, 'myCourses'])->name('my-courses');
    Route::get('/started', [QuizController::class, 'started'])->name('quizzes.started');
    Route::get('/quizzes/{id}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{id}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
});

// Admin/Teacher User Routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
