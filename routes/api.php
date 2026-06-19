<?php

use App\Http\Controllers\Api\AttemptController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ScoreController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/', function () {
        return request()->wantsJson()
            ? response()->json(['message' => 'Online Quiz System API v1', 'login' => 'POST /api/v1/login'])
            : view('api.index');
    });

    Route::post('/login', [AuthController::class, 'login'])->name('api.login');

    // Browser-friendly API preview. Open this in Chrome to avoid plain JSON text.
    Route::get('/quizzes', [QuizController::class, 'index']);
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show']);
    Route::get('/quizzes/{quiz}/questions', [QuestionController::class, 'indexByQuiz']);
    Route::get('/quizzes/{quiz}/results', [ReportController::class, 'quizResults']);
    Route::get('/quizzes/{quiz}/analytics', [ReportController::class, 'analytics']);
    Route::get('/reports/quiz/{quiz}/json', [ReportController::class, 'exportJSON']);

    Route::middleware('api.token')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/quizzes', [QuizController::class, 'store']);
        Route::put('/quizzes/{quiz}', [QuizController::class, 'update']);
        Route::patch('/quizzes/{quiz}', [QuizController::class, 'update']);
        Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy']);

        Route::apiResource('questions', QuestionController::class);
        Route::apiResource('options', OptionController::class)->except(['index', 'show']);
        Route::apiResource('attempts', AttemptController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
        Route::get('/scores/my-scores', [ScoreController::class, 'myScoresAPI']);
    });
});
