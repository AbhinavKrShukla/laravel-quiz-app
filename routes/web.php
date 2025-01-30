<?php

use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('backend.layouts.dashboard');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group([], function () {
    Route::resource('quiz', QuizController::class);
    Route::resource('question', QuestionController::class);
    Route::get('quiz/{id}/questions', [QuizController::class, 'questions'])->name('quiz.questions');
    Route::resource('user', UserController::class);
});
