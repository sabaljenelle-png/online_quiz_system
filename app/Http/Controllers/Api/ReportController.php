<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;

class ReportController extends Controller
{
    public function quizResults(Quiz $quiz)
    {
        return response()->json([
            'quiz' => $quiz->load('teacher'),
            'attempts' => $quiz->attempts()->with('student')->latest()->get(),
        ]);
    }

    public function analytics(Quiz $quiz)
    {
        $attempts = $quiz->attempts();

        return response()->json([
            'quiz_id' => $quiz->id,
            'title' => $quiz->title,
            'questions_count' => $quiz->questions()->count(),
            'attempts_count' => $attempts->count(),
            'average_score' => round((float) $quiz->attempts()->avg('score'), 2),
            'passed_count' => $quiz->attempts()->where('is_passed', true)->count(),
            'failed_count' => $quiz->attempts()->where('is_passed', false)->count(),
        ]);
    }

    public function exportJSON(Quiz $quiz)
    {
        return response()->json([
            'quiz' => $quiz->load(['teacher', 'questions.options', 'attempts.student']),
        ]);
    }
}
