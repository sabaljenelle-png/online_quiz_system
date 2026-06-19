<x-app-layout>
    <div class="bg-slate-100 min-h-screen py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Quiz Result</h1>
                        <p class="text-xl font-semibold text-slate-800 mt-2">{{ $quiz->title }}</p>
                        <p class="text-slate-600 mt-1">{{ $quiz->description ?: 'Review your answers below.' }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('scores.my-scores') }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">Back to My Scores</a>
                        <a href="{{ route('quizzes.available') }}" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Available Quizzes</a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow p-8 text-center">
                <div class="mx-auto h-16 w-16 rounded-full {{ $attempt->is_passed ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} flex items-center justify-center text-3xl mb-4">
                    {{ $attempt->is_passed ? '✓' : '!' }}
                </div>
                <h2 class="text-2xl font-bold text-slate-900">
                    {{ $attempt->status === 'completed' ? 'Quiz successfully submitted!' : 'Quiz in progress' }}
                </h2>
                <p class="text-slate-600 mt-2">Here is the detailed answer review.</p>
                @php
                    $attemptNumber = \App\Models\Attempt::where('student_id', $attempt->student_id)->where('quiz_id', $attempt->quiz_id)->where('id', '<=', $attempt->id)->count();
                    $totalAttemptsForQuiz = \App\Models\Attempt::where('student_id', $attempt->student_id)->where('quiz_id', $attempt->quiz_id)->count();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mt-6">
                    <div class="bg-slate-50 rounded-xl p-4">
                        <div class="text-3xl font-bold text-indigo-600">{{ $attempt->score ?? 0 }}%</div>
                        <div class="text-sm text-slate-600">Score</div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <div class="text-3xl font-bold {{ $attempt->is_passed ? 'text-green-600' : 'text-red-600' }}">{{ $attempt->is_passed ? 'Passed' : 'Failed' }}</div>
                        <div class="text-sm text-slate-600">Result</div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <div class="text-3xl font-bold text-indigo-600">{{ $attempt->total_score ?? 0 }}/{{ $quiz->questions->count() }}</div>
                        <div class="text-sm text-slate-600">Correct Answers</div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <div class="text-2xl font-bold text-slate-800">Attempt #{{ $attemptNumber }}</div>
                        <div class="text-sm text-slate-600">{{ $totalAttemptsForQuiz > 1 ? 'Retake record' : 'First attempt' }}</div>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4">
                        <div class="text-2xl font-bold text-slate-800">{{ $quiz->passing_score }}%</div>
                        <div class="text-sm text-slate-600">Passing Score</div>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @foreach($quiz->questions as $question)
                    @php
                        $selectedOptionIds = collect($answerMap[$question->id] ?? [])->map(fn($id) => (int) $id)->sort()->values()->all();
                        $correctOptionIds = $question->options->where('is_correct', true)->pluck('id')->map(fn($id) => (int) $id)->sort()->values()->all();
                        $selectedOptions = $question->options->whereIn('id', $selectedOptionIds);
                        $isCorrect = $correctOptionIds && $selectedOptionIds === $correctOptionIds;
                        $isCheckboxQuestion = ($question->question_type ?? $question->type) === 'multiple_choice' && ($question->answer_mode ?? 'radio') === 'checkbox';
                    @endphp
                    <div class="bg-white rounded-2xl shadow p-6 border {{ $isCorrect ? 'border-green-200' : 'border-red-200' }}">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wide {{ $isCorrect ? 'text-green-700' : 'text-red-700' }}">
                                    Question {{ $loop->iteration }} • {{ $isCorrect ? 'Correct' : 'Wrong' }}
                                </p>
                                <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $question->question_text }}</h3>
                            </div>
                            <span class="text-sm bg-slate-100 text-slate-700 rounded-full px-3 py-1">{{ $question->points }} point(s)</span>
                        </div>

                        <div class="space-y-2">
                            @foreach($question->options as $option)
                                @php
                                    $isSelected = in_array((int) $option->id, $selectedOptionIds, true);
                                    $classes = 'border-slate-200 bg-slate-50 text-slate-700';
                                    if ($option->is_correct) {
                                        $classes = 'border-green-300 bg-green-50 text-green-800';
                                    }
                                    if ($isSelected && ! $option->is_correct) {
                                        $classes = 'border-red-300 bg-red-50 text-red-800';
                                    }
                                @endphp
                                <div class="flex items-center justify-between rounded-lg border px-4 py-3 {{ $classes }}">
                                    <span>
                                        @if($option->is_correct)
                                            ✓ Correct answer: 
                                        @elseif($isSelected)
                                            ✕ Your answer: 
                                        @else
                                            • 
                                        @endif
                                        {{ $option->option_text }}
                                    </span>
                                    @if($isSelected)
                                        <span class="text-xs font-bold uppercase">Selected</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        @if($isCheckboxQuestion)
                            <p class="mt-3 text-sm text-blue-700 bg-blue-50 border border-blue-200 rounded-lg p-3">This was a checkbox question. Student needed to select exactly all correct answers.</p>
                        @endif
                        @if(count($selectedOptionIds) === 0)
                            <p class="mt-3 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-3">No answer was recorded for this question.</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-2xl shadow p-6 flex flex-col sm:flex-row justify-end gap-3">
                <a href="{{ route('scores.my-scores') }}" class="text-center px-5 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">Back to My Scores</a>
                <a href="{{ route('quizzes.available') }}" class="text-center px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Take Another Quiz</a>
            </div>
        </div>
    </div>
</x-app-layout>
