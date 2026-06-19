<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionController extends Controller
{
    private function ensureQuestionOwner(Question $question): void
    {
        $question->loadMissing('quiz');
        abort_unless(Auth::id() === (int) $question->quiz->teacher_id, 403, 'You can only manage your own quiz options.');
    }

    public function store(Request $request, Question $question)
    {
        $this->ensureQuestionOwner($question);

        $validated = $request->validate([
            'option_text' => 'required|string|max:500',
            'is_correct' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_correct') && ($question->answer_mode ?? 'radio') !== 'checkbox') {
            $question->options()->update(['is_correct' => false]);
        }

        $question->options()->create([
            'option_text' => $validated['option_text'],
            'is_correct' => $request->boolean('is_correct'),
            'order' => $question->options()->count() + 1,
        ]);

        return redirect()->route('questions.index', $question->quiz)->with('success', 'Option added successfully.');
    }

    public function update(Request $request, Question $question, Option $option)
    {
        $this->ensureQuestionOwner($question);
        abort_unless($option->question_id === $question->id, 404);

        $validated = $request->validate([
            'option_text' => 'required|string|max:500',
            'is_correct' => 'nullable|boolean',
        ]);

        if ($request->boolean('is_correct') && ($question->answer_mode ?? 'radio') !== 'checkbox') {
            $question->options()->where('id', '!=', $option->id)->update(['is_correct' => false]);
        }

        $option->update([
            'option_text' => $validated['option_text'],
            'is_correct' => $request->boolean('is_correct'),
        ]);

        return redirect()->route('questions.index', $question->quiz)->with('success', 'Option updated successfully.');
    }

    public function destroy(Question $question, Option $option)
    {
        $this->ensureQuestionOwner($question);
        abort_unless($option->question_id === $question->id, 404);

        $quiz = $question->quiz;
        $option->delete();

        return redirect()->route('questions.index', $quiz)->with('success', 'Option removed successfully.');
    }
}
