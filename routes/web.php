<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\AttemptController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'store']);
    Route::get('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'store']);
    Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\Auth\NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\Auth\NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/verify-email', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [\App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('/confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('/confirm-password', [\App\Http\Controllers\Auth\ConfirmablePasswordController::class, 'store']);
    Route::post('/logout', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // One clean /quizzes route:
    // teacher = manage own quizzes, student = available published quizzes.
    Route::get('/quizzes', function (\Illuminate\Http\Request $request) {
        if (auth()->user()->isStudent()) {
            return app(AttemptController::class)->availableQuizzes($request);
        }
        return app(QuizController::class)->index($request);
    })->name('quizzes.index');

    // Shared result page. Students can view their own result; teachers can view results for their own quizzes.
    // This is outside the student-only group so result links never fall back to the dashboard.
    Route::get('/attempts/{attempt}/result', [AttemptController::class, 'show'])->name('attempts.result');

    Route::middleware('role:student')->group(function () {
        Route::get('/quizzes/available', [AttemptController::class, 'availableQuizzes'])->name('quizzes.available');
        Route::get('/quizzes/{quiz}/take', [AttemptController::class, 'start'])->name('attempts.start');
        Route::get('/attempts/{attempt}/take', [AttemptController::class, 'take'])->name('attempts.take');
        Route::post('/attempts/{attempt}/submit', [AttemptController::class, 'submitAnswer'])->name('attempts.submit');
        Route::post('/attempts/{attempt}/complete', [AttemptController::class, 'complete'])->name('attempts.complete');
        Route::get('/attempts/{attempt}', [AttemptController::class, 'show'])->name('attempts.show');
        Route::get('/my-scores', [AttemptController::class, 'myScores'])->name('scores.my-scores');
    });

    Route::middleware('role:teacher')->group(function () {
        Route::get('/quizzes/import', [QuizController::class, 'importForm'])->name('quizzes.import-form');
        Route::get('/quizzes/import/sample', [QuizController::class, 'sampleImport'])->name('quizzes.import-sample');
        Route::post('/quizzes/import', [QuizController::class, 'import'])->name('quizzes.import');

        // Question routes are placed BEFORE the quiz resource routes so Add Question never falls back to another page.
        Route::prefix('quizzes/{quiz}/questions')->group(function () {
            Route::get('/', [QuestionController::class, 'index'])->name('questions.index');
            Route::get('/create', [QuestionController::class, 'create'])->name('questions.create');
            Route::post('/', [QuestionController::class, 'store'])->name('questions.store');
            Route::get('{question}', [QuestionController::class, 'show'])->name('questions.show');
            Route::get('{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
            Route::put('{question}', [QuestionController::class, 'update'])->name('questions.update');
            Route::delete('{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
            // POST fallback routes for local browsers/servers where method spoofing causes bad redirects.
            Route::post('{question}/update', [QuestionController::class, 'update'])->name('questions.update.post');
            Route::post('{question}/delete', [QuestionController::class, 'destroy'])->name('questions.destroy.post');
            Route::get('{question}/delete-now', [QuestionController::class, 'destroy'])->name('questions.destroy.get');
        });

        Route::match(['post', 'patch'], '/quizzes/{quiz}/publish', [QuizController::class, 'publish'])->name('quizzes.publish');
        Route::match(['post', 'patch'], '/quizzes/{quiz}/unpublish', [QuizController::class, 'unpublish'])->name('quizzes.unpublish');

        Route::resource('quizzes', QuizController::class)->except(['index'])->names([
            'create' => 'quizzes.create',
            'store' => 'quizzes.store',
            'show' => 'quizzes.show',
            'edit' => 'quizzes.edit',
            'update' => 'quizzes.update',
            'destroy' => 'quizzes.destroy',
        ]);

        Route::prefix('questions/{question}/options')->group(function () {
            Route::post('/', [OptionController::class, 'store'])->name('options.store');
            Route::put('{option}', [OptionController::class, 'update'])->name('options.update');
            Route::delete('{option}', [OptionController::class, 'destroy'])->name('options.destroy');
        });

        Route::get('/quizzes/{quiz}/results', [ReportController::class, 'quizResults'])->name('quizzes.results');
        Route::get('/quizzes/{quiz}/analytics', [ReportController::class, 'analytics'])->name('quizzes.analytics');
        Route::get('/reports/quiz/{quiz}/export-pdf', [ReportController::class, 'exportQuizResultsPDF'])->name('reports.quiz-pdf');
        Route::get('/reports/quiz/{quiz}/export-excel', [ReportController::class, 'exportQuizResultsExcel'])->name('reports.quiz-excel');
        Route::get('/reports/quiz/{quiz}/export-csv', [ReportController::class, 'exportQuizResultsCSV'])->name('reports.quiz-csv');
    });
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/users', [DashboardController::class, 'manageUsers'])->name('admin.users');
    Route::get('/reports', [ReportController::class, 'allReports'])->name('admin.reports');
    Route::get('/reports/export-all-pdf', [ReportController::class, 'exportAllResultsPDF'])->name('reports.all-pdf');
});

require __DIR__.'/auth.php';
