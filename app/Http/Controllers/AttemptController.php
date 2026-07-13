<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttemptController extends Controller
{
    public function availableQuizzes(Request $request)
    {
        $quizzes = Quiz::where('is_published', true)
            ->whereHas('questions.options')
            ->withCount('questions')
            ->with('teacher')
            ->latest()
            ->get();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($quizzes);
        }

        $attemptsByQuiz = Attempt::where('student_id', Auth::id())
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->latest()
            ->get()
            ->groupBy('quiz_id');

        return view('quizzes.available', compact('quizzes', 'attemptsByQuiz'));
    }

    public function start(Quiz $quiz, Request $request)
    {
        $user = Auth::user();

        if (! $quiz->is_published) {
            return back()->with('error', 'This quiz is not published yet.');
        }

        if ($quiz->questions()->count() === 0 || ! $quiz->questions()->whereHas('options')->exists()) {
            return back()->with('error', 'This quiz has no questions/options yet. Please ask the teacher to finish setup.');
        }

        if ($user && ! $user->isStudent()) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Only students can take quizzes.'], 403);
            }
            return back()->with('error', 'Only students can take quizzes.');
        }

        // Continue an unfinished attempt if one exists. If the latest attempt is completed,
        // create a NEW attempt for retake so teachers can see retake history.
        $attempt = Attempt::where('student_id', Auth::id())
            ->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')
            ->latest()
            ->first();

        if (! $attempt) {
            $attempt = Attempt::create([
                'student_id' => Auth::id(),
                'quiz_id' => $quiz->id,
                'status' => 'in_progress',
                'started_at' => now(),
            ]);
        }

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($attempt->load('quiz.questions.options'));
        }

        return redirect()->route('attempts.take', $attempt);
    }

    public function submitAnswer(Request $request, Attempt $attempt)
    {
        abort_unless($attempt->student_id === Auth::id(), 403);

        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_id' => 'required|exists:options,id',
        ]);

        $attempt->questions()->syncWithoutDetaching([
            $validated['question_id'] => ['option_id' => $validated['option_id']],
        ]);

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Answer saved successfully.']);
        }

        return back()->with('success', 'Answer saved successfully.');
    }

    public function complete(Request $request, Attempt $attempt)
{
    abort_unless($attempt->student_id === Auth::id(), 403);

    if ($attempt->status === 'completed') {
        return redirect()->route('attempts.result', $attempt)
            ->with('success', 'This quiz was already submitted.');
    }

    $attempt->load('quiz.questions.options');
    $quiz = $attempt->quiz;
    $answers = $request->input('answers', []);

    // Check if all questions are answered
    foreach ($quiz->questions as $question) {
        if (!array_key_exists($question->id, $answers) || blank($answers[$question->id])) {
            return back()
                ->withInput()
                ->with('error', 'Please answer all questions before submitting.');
        }
    }

    // Save answers
    foreach ($answers as $questionId => $answerValue) {

        $question = $quiz->questions->firstWhere('id', (int)$questionId);

        if (!$question) {
            continue;
        }

        DB::table('attempt_question')
            ->where('attempt_id', $attempt->id)
            ->where('question_id', $question->id)
            ->delete();

        $selectedOptionIds = is_array($answerValue)
            ? $answerValue
            : [$answerValue];

        foreach ($selectedOptionIds as $optionId) {

            DB::table('attempt_question')->insert([
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'option_id' => $optionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    // Get saved answers
    $userAnswers = DB::table('attempt_question')
        ->where('attempt_id', $attempt->id)
        ->get()
        ->groupBy('question_id')
        ->map(function ($rows) {
            return $rows->pluck('option_id')
                ->map(fn($id) => (int)$id)
                ->unique()
                ->values();
        });

    $totalQuestions = $quiz->questions->count();
    $correctAnswers = 0;

    foreach ($quiz->questions as $question) {

        $selectedOptionIds = collect($userAnswers->get($question->id, []))
            ->map(fn($id) => (int)$id)
            ->unique()
            ->values();

        $correctOptionIds = $question->options
            ->where('is_correct', true)
            ->pluck('id')
            ->map(fn($id) => (int)$id)
            ->values();

        $correctCount = $correctOptionIds->count();

        if ($correctCount == 0) {
            continue;
        }

        // SINGLE CHOICE
        if ($correctCount == 1) {

            if (
                $selectedOptionIds->count() == 1 &&
                $selectedOptionIds->first() == $correctOptionIds->first()
            ) {
                $correctAnswers += 1;
            }

            continue;
        }

        // MULTIPLE SELECT (PARTIAL SCORING)

        $matched = $selectedOptionIds
            ->intersect($correctOptionIds)
            ->count();

        $wrongSelected = $selectedOptionIds
            ->diff($correctOptionIds)
            ->count();

        // No points kapag may maling napili
        if ($wrongSelected == 0) {
            $correctAnswers += $matched / $correctCount;
        }
    }

    $scorePercentage = $totalQuestions > 0
        ? round(($correctAnswers / $totalQuestions) * 100, 2)
        : 0;

    $attempt->update([
        'score' => $scorePercentage,
        'total_score' => round($correctAnswers, 2),
        'is_passed' => $scorePercentage >= $quiz->passing_score,
        'status' => 'completed',
        'completed_at' => now(),
    ]);

    return redirect()
        ->route('attempts.result', $attempt)
        ->with('success', 'Quiz successfully submitted!');
}

    public function show(Attempt $attempt, Request $request)
    {
        abort_unless($attempt->student_id === Auth::id() || $attempt->quiz?->teacher_id === Auth::id(), 403);

        $attempt->load(['quiz.questions.options']);
        $quiz = $attempt->quiz;

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($attempt);
        }

        $answerMap = DB::table('attempt_question')
            ->where('attempt_id', $attempt->id)
            ->get()
            ->groupBy('question_id')
            ->map(fn ($rows) => $rows->pluck('option_id')->map(fn ($id) => (int) $id)->unique()->values()->all());

        return view('attempts.show', compact('attempt', 'quiz', 'answerMap'));
    }

    public function take(Attempt $attempt)
    {
        abort_unless($attempt->student_id === Auth::id(), 403);

        $attempt->load('quiz.questions.options');
        $quiz = $attempt->quiz;

        if ($attempt->status === 'completed') {
            return redirect()->route('attempts.result', $attempt)->with('success', 'This quiz was already submitted.');
        }

        return view('attempts.take', compact('attempt', 'quiz'));
    }

    public function myScores(Request $request)
    {
        $attempts = Attempt::with('quiz')
            ->where('student_id', Auth::id())
            ->where('status', 'completed')
            ->latest()
            ->get();

        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json($attempts);
        }

        return view('scores.index', compact('attempts'));
    }
}
