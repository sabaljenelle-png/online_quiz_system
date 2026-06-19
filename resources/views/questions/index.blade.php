<x-app-layout>
    <div class="bg-slate-100 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Manage Questions</h1>
                        <p class="text-slate-600 mt-1">Quiz: <span class="font-semibold">{{ $quiz->title }}</span></p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('quizzes.show', $quiz) }}" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">Back to Quiz</a>
                        <a href="{{ route('questions.create', $quiz) }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">+ Add Question</a>
                    </div>
                </div>
            </div>

            @forelse($questions as $index => $question)
                <div class="bg-white shadow rounded-2xl p-6 border border-slate-200">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 border-b border-slate-200 pb-4 mb-4">
                        <div>
                            <span class="text-xs font-bold uppercase px-2 py-1 bg-slate-100 text-slate-600 rounded">Question #{{ $index + 1 }}</span>
                            <h3 class="text-lg font-semibold text-slate-900 mt-2">{{ $question->question_text }}</h3>
                            <p class="text-sm text-slate-500 mt-1">{{ str_replace('_', ' ', ucfirst($question->question_type ?? $question->type)) }}{{ ($question->question_type ?? $question->type) === 'multiple_choice' ? ' • ' . ucfirst($question->answer_mode ?? 'radio') : '' }} • {{ $question->points }} point(s)</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('questions.edit', [$quiz, $question]) }}" class="text-sm bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded-lg">Edit</a>
                            <a href="{{ url('/quizzes/' . $quiz->id . '/questions/' . $question->id . '/delete-now') }}" onclick="return confirm('Delete this question? This will also remove its choices and saved answers.');" class="text-sm bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-lg">Delete</a>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-slate-700 mb-3">Answer Choices</h4>
                        <div class="space-y-2">
                            @forelse($question->options as $option)
                                <div class="flex items-center gap-3 p-3 rounded-lg border {{ $option->is_correct ? 'bg-green-50 border-green-300 text-green-800' : 'bg-slate-50 border-slate-200' }}">
                                    @if($option->is_correct)
                                        <span class="text-green-600 font-bold">✓ Correct:</span>
                                    @else
                                        <span class="text-slate-400 font-medium">• Choice:</span>
                                    @endif
                                    <span class="text-sm">{{ $option->option_text }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-amber-700 bg-amber-50 p-3 rounded border border-amber-200">No choices yet. Click Edit to add answer choices.</p>
                            @endforelse
                        </div>
                        <p class="mt-3 text-xs text-slate-500">To add, remove, or mark a correct answer, click <span class="font-semibold">Edit</span>.</p>
                    </div>
                </div>
            @empty
                <div class="bg-white shadow rounded-2xl p-12 text-center border border-slate-200">
                    <p class="text-slate-500 text-lg">This quiz doesn't have questions yet.</p>
                    <a href="{{ route('questions.create', $quiz) }}" class="inline-block mt-4 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">Create First Question</a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
