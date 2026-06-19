<x-app-layout>
    <div class="bg-slate-100 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Available Quizzes</h1>
                        <p class="text-slate-600 mt-2">Published quizzes from teachers will appear here automatically.</p>
                    </div>
                    <a href="{{ route('scores.my-scores') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-center">My Scores</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($quizzes as $quiz)
                    @php
                        $latestAttempt = $attemptsByQuiz[$quiz->id][0] ?? null;
                    @endphp
                    <div class="bg-white rounded-2xl shadow p-6 border border-slate-200 flex flex-col">
                        <div class="flex justify-between items-start gap-3">
                            <h2 class="text-xl font-bold text-slate-900">{{ $quiz->title }}</h2>
                            <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">Published</span>
                        </div>
                        <p class="text-slate-600 mt-2 min-h-12 flex-1">{{ $quiz->description ?: 'No description.' }}</p>
                        <p class="text-xs text-slate-500 mt-3">Teacher: {{ $quiz->teacher->name ?? 'Teacher' }}</p>

                        <div class="grid grid-cols-3 gap-2 text-center text-sm mt-5">
                            <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $quiz->questions_count ?? $quiz->questions()->count() }}</strong><br>Questions</div>
                            <div class="bg-slate-50 rounded-lg p-3"><strong>{{ (int) round((float) $quiz->duration) }}</strong><br>Minutes</div>
                            <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $quiz->passing_score }}%</strong><br>Pass</div>
                        </div>

                        @if($latestAttempt && $latestAttempt->status === 'completed')
                            <div class="mt-5 bg-green-50 border border-green-200 text-green-800 rounded-lg p-3 text-sm">
                                Completed • Score: <strong>{{ $latestAttempt->score ?? 0 }}%</strong><br><span class="text-xs">{{ ($attemptsByQuiz[$quiz->id]->count() ?? 1) > 1 ? 'Retake attempt #' . ($attemptsByQuiz[$quiz->id]->count()) : 'First attempt' }} • {{ $latestAttempt->is_passed ? 'Passed' : 'Failed' }} • Passing score {{ $quiz->passing_score }}%</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2 mt-3">
                                <a href="{{ route('attempts.result', $latestAttempt) }}" class="text-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">View Result</a>
                                <a href="{{ route('attempts.start', $quiz) }}" class="text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Retake</a>
                            </div>
                        @elseif($latestAttempt && $latestAttempt->status === 'in_progress')
                            <a href="{{ route('attempts.take', $latestAttempt) }}" class="block mt-5 text-center bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700">Continue Quiz</a>
                        @else
                            <a href="{{ route('attempts.start', $quiz) }}" class="block mt-5 text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Take Quiz</a>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl shadow p-10 text-center">
                        <p class="text-slate-500 text-lg">No available quizzes yet.</p>
                        <p class="text-slate-400 mt-2">A quiz will show here after a teacher adds questions and publishes it.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
