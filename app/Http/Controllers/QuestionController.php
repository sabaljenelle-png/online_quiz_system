<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class QuestionController extends Controller
{
    private function ensureOwner(Quiz $quiz): void
    {
        abort_unless(Auth::check() && Auth::id() === (int) $quiz->teacher_id, 403, 'You can only manage your own quizzes.');
    }

    public function index(Quiz $quiz)
    {
        $this->ensureOwner($quiz);
        $questions = $quiz->questions()->with('options')->orderBy('order')->get();
        return view('questions.index', compact('quiz', 'questions'));
    }

    public function create(Quiz $quiz)
    {
        $this->ensureOwner($quiz);
        return view('questions.create', compact('quiz'));
    }

    private function validatedQuestionData(Request $request): array
    {
        $validated = $request->validate([
            'question_text' => ['required', 'string', 'max:1000'],
            'question_type' => ['required', 'in:multiple_choice,true_false'],
            'answer_mode' => ['nullable', 'in:radio,checkbox'],
            'points' => ['required', 'integer', 'min:1', 'max:100'],
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string', 'max:500'],
            'correct_option' => ['nullable', 'integer', 'min:0', 'max:200'],
            'correct_options' => ['nullable', 'array'],
            'correct_options.*' => ['integer', 'min:0', 'max:200'],
            'true_false_answer' => ['nullable', 'in:true,false'],
        ]);

        if (($validated['question_type'] ?? 'multiple_choice') !== 'multiple_choice') {
            $validated['answer_mode'] = 'radio';
        } else {
            $validated['answer_mode'] = $validated['answer_mode'] ?? 'radio';
        }

        return $validated;
    }

    private function cleanOptions(Request $request): array
    {
        $cleanOptions = [];
        foreach ($request->input('options', []) as $index => $optionText) {
            $optionText = trim((string) $optionText);
            if ($optionText === '') {
                continue;
            }
            $cleanOptions[] = [
                'text' => $optionText,
                'original_index' => (int) $index,
            ];
        }

        if (count($cleanOptions) < 2) {
            throw ValidationException::withMessages([
                'options' => 'Add at least two choices before saving the question.',
            ]);
        }

        return $cleanOptions;
    }

    private function saveOptions(Question $question, Request $request, array $validated): void
    {
        if ($validated['question_type'] === 'true_false') {
            $answer = $request->input('true_false_answer', 'true');

            $question->options()->create([
                'option_text' => 'True',
                'is_correct' => $answer === 'true',
                'order' => 1,
            ]);

            $question->options()->create([
                'option_text' => 'False',
                'is_correct' => $answer === 'false',
                'order' => 2,
            ]);

            return;
        }

        $cleanOptions = $this->cleanOptions($request);
        $answerMode = $validated['answer_mode'] ?? 'radio';

        if ($answerMode === 'checkbox') {
            $correctIndexes = collect($request->input('correct_options', []))
                ->map(fn ($value) => (int) $value)
                ->unique()
                ->values()
                ->all();

            $validOriginalIndexes = collect($cleanOptions)->pluck('original_index')->all();
            $validCorrectIndexes = array_values(array_intersect($correctIndexes, $validOriginalIndexes));

            if (count($validCorrectIndexes) === 0) {
                throw ValidationException::withMessages([
                    'correct_options' => 'For checkbox questions, mark at least one correct answer.',
                ]);
            }

            foreach ($cleanOptions as $index => $option) {
                $question->options()->create([
                    'option_text' => $option['text'],
                    'is_correct' => in_array($option['original_index'], $validCorrectIndexes, true),
                    'order' => $index + 1,
                ]);
            }

            return;
        }

        $correctIndex = (int) $request->input('correct_option', -1);
        $selectedCorrectOption = collect($cleanOptions)->firstWhere('original_index', $correctIndex);

        if (! $selectedCorrectOption) {
            throw ValidationException::withMessages([
                'correct_option' => 'Select the correct answer and make sure the selected choice is not blank.',
            ]);
        }

        foreach ($cleanOptions as $index => $option) {
            $question->options()->create([
                'option_text' => $option['text'],
                'is_correct' => $option['original_index'] === $correctIndex,
                'order' => $index + 1,
            ]);
        }
    }

    public function store(Request $request, Quiz $quiz)
    {
        $this->ensureOwner($quiz);
        $validated = $this->validatedQuestionData($request);

        try {
            DB::transaction(function () use ($request, $quiz, $validated) {
                $question = $quiz->questions()->create([
                    'question_text' => $validated['question_text'],
                    'question_type' => $validated['question_type'],
                    'type' => $validated['question_type'],
                    'answer_mode' => $validated['answer_mode'] ?? 'radio',
                    'points' => $validated['points'],
                    'order' => $quiz->questions()->count() + 1,
                ]);

                $this->saveOptions($question, $request, $validated);
            });
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Question was not saved: ' . $e->getMessage());
        }

        return redirect()->to('/quizzes/' . $quiz->id)->with('success', 'Question added successfully.');
    }

    public function show(Quiz $quiz, Question $question)
    {
        $this->ensureOwner($quiz);
        abort_unless($question->quiz_id === $quiz->id, 404);
        $question->load('options');
        return view('questions.show', compact('quiz', 'question'));
    }

    public function edit(Quiz $quiz, Question $question)
    {
        $this->ensureOwner($quiz);
        abort_unless($question->quiz_id === $quiz->id, 404);
        $question->load('options');
        return view('questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, Question $question)
    {
        $this->ensureOwner($quiz);
        abort_unless($question->quiz_id === $quiz->id, 404);
        $validated = $this->validatedQuestionData($request);

        try {
            DB::transaction(function () use ($request, $question, $validated) {
                $question->update([
                    'question_text' => $validated['question_text'],
                    'question_type' => $validated['question_type'],
                    'type' => $validated['question_type'],
                    'answer_mode' => $validated['answer_mode'] ?? 'radio',
                    'points' => $validated['points'],
                ]);

                DB::table('attempt_question')->where('question_id', $question->id)->delete();
                $question->options()->delete();
                $this->saveOptions($question, $request, $validated);
            });
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Question was not updated: ' . $e->getMessage());
        }

        return redirect()->to('/quizzes/' . $quiz->id . '/questions')->with('success', 'Question updated successfully.');
    }

    public function destroy(Quiz $quiz, Question $question)
    {
        $this->ensureOwner($quiz);
        abort_unless($question->quiz_id === $quiz->id, 404);

        try {
            DB::transaction(function () use ($question) {
                DB::table('attempt_question')->where('question_id', $question->id)->delete();
                $question->scores()->delete();
                $question->options()->delete();
                $question->delete();
            });
        } catch (\Throwable $e) {
            return redirect()->to('/quizzes/' . $quiz->id . '/questions')->with('error', 'Question was not deleted: ' . $e->getMessage());
        }

        return redirect()->to('/quizzes/' . $quiz->id . '/questions')->with('success', 'Question deleted successfully.');
    }
}
