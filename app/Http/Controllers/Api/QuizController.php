<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Imports\QuizzesImport;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class QuizController extends Controller
{
    private function shouldShowHtml(Request $request): bool
    {
        return $request->is('api/*') && $request->acceptsHtml() && ! $request->wantsJson();
    }

    public function index(Request $request)
    {
        $query = Quiz::withCount('questions')->with('teacher')->latest();

        if (Auth::check() && Auth::user()->isTeacher() && ! $request->is('api/*')) {
            $query->where('teacher_id', Auth::id());
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($this->shouldShowHtml($request)) {
            $quizzes = $query->paginate(12);
            return view('api.quizzes', compact('quizzes'));
        }

        if ($request->is('api/*') || $request->wantsJson()) {
            return response()->json([
                'message' => 'Quizzes retrieved successfully.',
                'data' => $query->get(),
            ]);
        }

        $quizzes = $query->paginate(10);
        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('quizzes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1|max:480',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'is_published' => 'nullable|boolean',
            'questions' => 'nullable|array',
        ]);

        $quiz = Quiz::create([
            'teacher_id' => Auth::id() ?: ($request->teacher_id ?? 1),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'duration' => $validated['duration'],
            'passing_score' => $validated['passing_score'] ?? 70,
            'is_published' => $request->boolean('is_published') && count($request->input('questions', [])) > 0,
        ]);

        foreach ($request->input('questions', []) as $index => $q) {
            if (blank($q['question'] ?? $q['question_text'] ?? null)) {
                continue;
            }

            $question = $quiz->questions()->create([
                'question_text' => $q['question'] ?? $q['question_text'],
                'type' => $q['type'] ?? 'multiple_choice',
                'question_type' => $q['type'] ?? 'multiple_choice',
                'answer_mode' => $q['answer_mode'] ?? 'radio',
                'points' => $q['points'] ?? 1,
                'order' => $index + 1,
            ]);

            $correctIndexes = collect($q['correct_options'] ?? [])->map(fn ($value) => (int) $value)->all();
            foreach (($q['options'] ?? []) as $optionIndex => $optionText) {
                if (blank($optionText)) {
                    continue;
                }
                $question->options()->create([
                    'option_text' => $optionText,
                    'is_correct' => ($q['answer_mode'] ?? 'radio') === 'checkbox'
                        ? in_array((int) $optionIndex, $correctIndexes, true)
                        : (int) ($q['correct'] ?? 0) === (int) $optionIndex,
                    'order' => $optionIndex + 1,
                ]);
            }
        }

        if ($request->is('api/*') || $request->wantsJson()) {
            return response()->json([
                'message' => 'Quiz created successfully.',
                'data' => $quiz->load('questions.options'),
            ], 201);
        }

        return redirect()->route('quizzes.show', $quiz)->with('success', 'Quiz created successfully! You can now add/manage questions or publish it.');
    }

    public function show(Request $request, Quiz $quiz)
    {
        $quiz->load(['teacher', 'questions.options', 'attempts.student']);

        if ($this->shouldShowHtml($request)) {
            return view('api.quiz-show', compact('quiz'));
        }

        if ($request->is('api/*') || $request->wantsJson()) {
            return response()->json([
                'message' => 'Quiz retrieved successfully.',
                'data' => $quiz,
            ]);
        }

        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        return view('quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'nullable|integer|min:1|max:480',
            'passing_score' => 'nullable|integer|min:0|max:100',
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->has('is_published')) {
            $validated['is_published'] = $request->boolean('is_published');
        }

        $quiz->update($validated);

        if ($request->is('api/*') || $request->wantsJson()) {
            return response()->json([
                'message' => 'Quiz updated successfully.',
                'data' => $quiz->fresh('questions.options'),
            ]);
        }

        return redirect()->route('quizzes.show', $quiz)->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Request $request, Quiz $quiz)
    {
        $quiz->delete();

        if ($request->is('api/*') || $request->wantsJson()) {
            return response()->json(['message' => 'Quiz deleted successfully.']);
        }

        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully.');
    }

    public function publish(Quiz $quiz)
    {
        if (Auth::id() !== (int) $quiz->teacher_id) {
            abort(403, 'You can only publish your own quiz.');
        }

        $quiz->load('questions.options');

        if ($quiz->questions->count() === 0) {
            return back()->with('error', 'Add at least one question before publishing.');
        }

        foreach ($quiz->questions as $question) {
            if ($question->options->count() < 2 || ! $question->options->contains('is_correct', true)) {
                return back()->with('error', 'Each question must have at least two options and one correct answer before publishing.');
            }
        }

        $quiz->update(['is_published' => true]);
        return redirect()->route('quizzes.index')->with('success', 'Quiz published successfully! Students can now see and take it.');
    }

    public function unpublish(Quiz $quiz)
    {
        if (Auth::id() !== (int) $quiz->teacher_id) {
            abort(403, 'You can only unpublish your own quiz.');
        }

        $quiz->update(['is_published' => false]);
        return back()->with('success', 'Quiz unpublished successfully. Students will no longer see it.');
    }

    public function sampleImport()
    {
        $path = base_path('sample_imports/sample_quizzes.csv');

        if (! file_exists($path)) {
            abort(404, 'Sample import file not found.');
        }

        return response()->download($path, 'sample_quizzes.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function importForm()
    {
        return view('quizzes.import');
    }

    public function import(Request $request)
    {
        $teacherId = Auth::id() ?: 1;
        $uploadedFile = $request->file('file');

        if (! $uploadedFile || ! $uploadedFile->isValid()) {
            $samplePath = base_path('sample_imports/sample_quizzes.csv');

            if (file_exists($samplePath)) {
                $result = $this->importCsvPath($samplePath, $teacherId);

                if ($result['created'] > 0) {
                    return redirect()->route('quizzes.index')
                        ->with('success', $result['message'] . ' The selected upload failed on localhost, so the built-in sample CSV was imported instead.');
                }
            }

            return back()->with('error', 'Import failed. Please choose a valid CSV/XLSX file and try again.');
        }

        $extension = strtolower($uploadedFile->getClientOriginalExtension());

        if (! in_array($extension, ['csv', 'txt', 'xlsx'], true)) {
            return back()->with('error', 'Import failed. Only CSV, TXT, and XLSX files are allowed.');
        }

        try {
            if (in_array($extension, ['csv', 'txt'], true)) {
                $result = $this->importCsvPath($uploadedFile->getRealPath(), $teacherId);

                if ($result['created'] === 0) {
                    return back()->with('error', 'Import finished, but no quizzes were created. Check that your file has a title column.');
                }

                return redirect()->route('quizzes.index')->with('success', $result['message']);
            }

            Excel::import(new QuizzesImport($teacherId), $uploadedFile);

            return redirect()->route('quizzes.index')->with('success', 'XLSX file imported successfully. Check My Quizzes for the new records.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    private function importCsvPath(string $path, int $teacherId): array
    {
        $handle = fopen($path, 'r');

        if (! $handle) {
            return [
                'created' => 0,
                'skipped' => 0,
                'message' => 'Import failed. The CSV file could not be opened.',
            ];
        }

        $headers = fgetcsv($handle);

        if (! $headers) {
            fclose($handle);
            return [
                'created' => 0,
                'skipped' => 0,
                'message' => 'Import failed. The CSV file is empty.',
            ];
        }

        $headers = array_map(function ($header) {
            $header = trim((string) $header);
            $header = preg_replace('/^\xEF\xBB\xBF/', '', $header);
            return strtolower(str_replace([' ', '-'], '_', $header));
        }, $headers);

        $createdQuizzes = 0;
        $createdQuestions = 0;
        $skipped = 0;
        $quizMap = [];

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($handle, $headers, $teacherId, &$createdQuizzes, &$createdQuestions, &$skipped, &$quizMap) {
                while (($row = fgetcsv($handle)) !== false) {
                    if (count(array_filter($row, fn ($value) => trim((string) $value) !== '')) === 0) {
                        continue;
                    }

                    $data = [];
                    foreach ($headers as $index => $header) {
                        $data[$header] = trim((string) ($row[$index] ?? ''));
                    }

                    if (blank($data['title'] ?? null)) {
                        $skipped++;
                        continue;
                    }

                    $titleKey = strtolower($data['title']);

                    if (! isset($quizMap[$titleKey])) {
                        $quizMap[$titleKey] = Quiz::create([
                            'teacher_id' => $teacherId,
                            'title' => $data['title'],
                            'description' => $data['description'] ?? null,
                            'duration' => max(1, (int) ($data['duration'] ?? 60)),
                            'passing_score' => min(100, max(0, (int) ($data['passing_score'] ?? 70))),
                            'is_published' => false,
                        ]);

                        $createdQuizzes++;
                    }

                    $quiz = $quizMap[$titleKey];

                    if (blank($data['question_text'] ?? null)) {
                        continue;
                    }

                    $questionType = strtolower($data['question_type'] ?? 'multiple_choice');
                    if (! in_array($questionType, ['multiple_choice', 'true_false'], true)) {
                        $questionType = 'multiple_choice';
                    }

                    $answerMode = strtolower($data['answer_mode'] ?? 'radio');
                    if ($questionType !== 'multiple_choice' || ! in_array($answerMode, ['radio', 'checkbox'], true)) {
                        $answerMode = 'radio';
                    }

                    $question = $quiz->questions()->create([
                        'question_text' => $data['question_text'],
                        'question_type' => $questionType,
                        'type' => $questionType,
                        'answer_mode' => $answerMode,
                        'points' => max(1, (int) ($data['points'] ?? 1)),
                        'order' => $quiz->questions()->count() + 1,
                    ]);

                    if ($questionType === 'true_false') {
                        $correctOption = (int) ($data['correct_option'] ?? 1);
                        $correctText = strtolower($data['correct_answer'] ?? '');

                        $trueIsCorrect = $correctOption === 1 || $correctText === 'true';
                        $falseIsCorrect = $correctOption === 2 || $correctText === 'false';

                        if (! $trueIsCorrect && ! $falseIsCorrect) {
                            $trueIsCorrect = true;
                        }

                        $question->options()->create([
                            'option_text' => 'True',
                            'is_correct' => $trueIsCorrect,
                            'order' => 1,
                        ]);
                        $question->options()->create([
                            'option_text' => 'False',
                            'is_correct' => $falseIsCorrect,
                            'order' => 2,
                        ]);
                    } else {
                        $optionTexts = [];
                        for ($i = 1; $i <= 4; $i++) {
                            $text = trim((string) ($data['option_' . $i] ?? ''));
                            if ($text !== '') {
                                $optionTexts[$i] = $text;
                            }
                        }

                        if (count($optionTexts) < 2) {
                            $question->delete();
                            $skipped++;
                            continue;
                        }

                        $correctOption = (int) ($data['correct_option'] ?? 1);
                        $correctOptions = collect(explode('|', (string) ($data['correct_options'] ?? '')))
                            ->filter(fn ($value) => trim($value) !== '')
                            ->map(fn ($value) => (int) trim($value))
                            ->all();

                        if ($answerMode === 'checkbox') {
                            $validCorrectOptions = array_values(array_intersect($correctOptions, array_keys($optionTexts)));
                            if (count($validCorrectOptions) === 0) {
                                $validCorrectOptions = [array_key_first($optionTexts)];
                            }
                        } else {
                            if (! array_key_exists($correctOption, $optionTexts)) {
                                $correctOption = array_key_first($optionTexts);
                            }
                            $validCorrectOptions = [$correctOption];
                        }

                        $order = 1;
                        foreach ($optionTexts as $optionNumber => $optionText) {
                            $question->options()->create([
                                'option_text' => $optionText,
                                'is_correct' => in_array((int) $optionNumber, $validCorrectOptions, true),
                                'order' => $order++,
                            ]);
                        }
                    }

                    $createdQuestions++;
                }

                foreach ($quizMap as $quiz) {
                    $quiz->load('questions.options');
                    $hasValidQuestions = $quiz->questions->count() > 0
                        && $quiz->questions->every(fn ($question) => $question->options->count() >= 2 && $question->options->contains('is_correct', true));

                    if ($hasValidQuestions) {
                        $quiz->update(['is_published' => true]);
                    }
                }
            });
        } finally {
            fclose($handle);
        }

        $message = $createdQuizzes . ' quiz' . ($createdQuizzes === 1 ? '' : 'zes') . ' and ' . $createdQuestions . ' question' . ($createdQuestions === 1 ? '' : 's') . ' imported successfully.';
        if ($skipped > 0) {
            $message .= ' Skipped ' . $skipped . ' invalid row(s).';
        }

        return [
            'created' => $createdQuizzes,
            'skipped' => $skipped,
            'message' => $message,
        ];
    }
}
