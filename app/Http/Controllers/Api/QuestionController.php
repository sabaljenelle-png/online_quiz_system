<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Questions retrieved successfully.',
            'data' => Question::with(['quiz', 'options'])->latest()->get(),
        ]);
    }

    public function indexByQuiz(Quiz $quiz)
    {
        return response()->json([
            'message' => 'Quiz questions retrieved successfully.',
            'data' => $quiz->questions()->with('options')->orderBy('order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
            'question_type' => 'nullable|in:multiple_choice,true_false,short_answer',
            'type' => 'nullable|in:multiple_choice,true_false,short_answer',
            'answer_mode' => 'nullable|in:radio,checkbox',
            'points' => 'nullable|integer|min:1|max:100',
            'order' => 'nullable|integer|min:0',
            'options' => 'nullable|array',
        ]);

        $validated['type'] = $validated['question_type'] ?? $validated['type'] ?? 'multiple_choice';
        $validated['question_type'] = $validated['type'];
        $validated['answer_mode'] = ($validated['type'] ?? 'multiple_choice') === 'multiple_choice' ? ($validated['answer_mode'] ?? 'radio') : 'radio';
        $validated['points'] = $validated['points'] ?? 1;
        $validated['order'] = $validated['order'] ?? ((Question::where('quiz_id', $validated['quiz_id'])->max('order') ?? 0) + 1);

        $question = Question::create(collect($validated)->except('options')->toArray());

        foreach ($request->input('options', []) as $index => $option) {
            $text = is_array($option) ? ($option['option_text'] ?? null) : $option;
            if (blank($text)) {
                continue;
            }
            $question->options()->create([
                'option_text' => $text,
                'is_correct' => (bool) (is_array($option) ? ($option['is_correct'] ?? false) : false),
                'order' => $index + 1,
            ]);
        }

        return response()->json([
            'message' => 'Question created successfully.',
            'data' => $question->load('options'),
        ], 201);
    }

    public function show(Question $question)
    {
        return response()->json([
            'message' => 'Question retrieved successfully.',
            'data' => $question->load(['quiz', 'options']),
        ]);
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question_text' => 'sometimes|required|string',
            'question_type' => 'nullable|in:multiple_choice,true_false,short_answer',
            'type' => 'nullable|in:multiple_choice,true_false,short_answer',
            'answer_mode' => 'nullable|in:radio,checkbox',
            'points' => 'nullable|integer|min:1|max:100',
            'order' => 'nullable|integer|min:0',
        ]);

        if (isset($validated['question_type'])) {
            $validated['type'] = $validated['question_type'];
        }
        if (isset($validated['type'])) {
            $validated['question_type'] = $validated['type'];
        }
        if (isset($validated['question_type']) && $validated['question_type'] !== 'multiple_choice') {
            $validated['answer_mode'] = 'radio';
        }

        $question->update($validated);

        return response()->json([
            'message' => 'Question updated successfully.',
            'data' => $question->load('options'),
        ]);
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json(['message' => 'Question deleted successfully.']);
    }
}
