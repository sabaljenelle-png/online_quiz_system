<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attempt;
use App\Models\Quiz;
use Illuminate\Http\Request;

class AttemptController extends Controller
{
    public function index()
    {
        return response()->json(Attempt::with(['student', 'quiz'])->latest()->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'quiz_id' => 'required|exists:quizzes,id',
        ]);

        $attempt = Attempt::firstOrCreate(
            ['student_id' => $validated['student_id'], 'quiz_id' => $validated['quiz_id']],
            ['status' => 'in_progress', 'started_at' => now()]
        );

        return response()->json($attempt->load('quiz.questions.options'), 201);
    }

    public function show(Attempt $attempt)
    {
        return response()->json($attempt->load(['student', 'quiz.questions.options']));
    }

    public function update(Request $request, Attempt $attempt)
    {
        $validated = $request->validate([
            'score' => 'nullable|integer|min:0|max:100',
            'is_passed' => 'nullable|boolean',
            'status' => 'nullable|string',
        ]);

        $attempt->update($validated);

        return response()->json($attempt);
    }

    public function destroy(Attempt $attempt)
    {
        $attempt->delete();

        return response()->json(['message' => 'Attempt deleted successfully.']);
    }

    public function availableQuizzesAPI()
    {
        return response()->json(Quiz::where('is_published', true)->withCount('questions')->get());
    }

    public function myAttemptsAPI()
    {
        return response()->json(Attempt::with('quiz')->latest()->get());
    }
}
