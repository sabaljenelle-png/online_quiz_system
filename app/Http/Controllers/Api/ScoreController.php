<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Quiz;
use App\Models\Score;

class ScoreController extends Controller
{
    public function byQuiz(Quiz $quiz)
    {
        return response()->json($quiz->scores()->with('attempt.student', 'question')->latest()->get());
    }

    public function byAttempt(Attempt $attempt)
    {
        return response()->json($attempt->scores()->with('question')->get());
    }

    public function myScoresAPI()
    {
        return response()->json(Score::with(['attempt.student', 'attempt.quiz', 'question'])->latest()->get());
    }
}
