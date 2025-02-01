<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\isAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false
]);


Route::get('/test', function (){
    dd(Auth::user());
});

Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/', [HomeController::class, 'index'])->name('admin.dashboard');

Route::middleware(['auth', isAdmin::class])->group( function () {

    Route::resource('quiz', QuizController::class);
    Route::resource('question', QuestionController::class);
    Route::get('quiz/{id}/questions', [QuizController::class, 'questions'])->name('quiz.questions');
    Route::resource('user', UserController::class);

    // Assign exam
    Route::get('exam/assign', [ExamController::class, 'create']);
    Route::post('exam/assign', [ExamController::class, 'assignExam'])->name('assign.exam');
    Route::get('exam/user', [ExamController::class, 'userExam'])->name('view.exam');
    Route::post('exam/remove', [ExamController::class, 'removeExam'])->name('exam.remove');
});

