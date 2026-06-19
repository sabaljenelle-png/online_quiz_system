<x-app-layout>
    <div class="min-h-screen bg-slate-100 py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">My Scores</h1>
                        <p class="text-slate-600 mt-2">View your submitted quizzes and results.</p>
                    </div>
                </div>
            </div>

            @if($attempts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-2xl shadow p-6">
                        <p class="text-sm text-slate-500">Completed Quizzes</p>
                        <p class="text-4xl font-bold text-indigo-600 mt-2">{{ $attempts->count() }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow p-6">
                        <p class="text-sm text-slate-500">Average Score</p>
                        <p class="text-4xl font-bold text-green-600 mt-2">{{ round($attempts->avg('score') ?? 0) }}%</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow p-6">
                        <p class="text-sm text-slate-500">Passed</p>
                        <p class="text-4xl font-bold text-emerald-600 mt-2">{{ $attempts->where('is_passed', true)->count() }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Quiz</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Attempt</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            @forelse($attempts as $attempt)
                                @php
                                    $attemptNumber = \App\Models\Attempt::where('student_id', $attempt->student_id)->where('quiz_id', $attempt->quiz_id)->where('id', '<=', $attempt->id)->count();
                                    $totalAttempts = \App\Models\Attempt::where('student_id', $attempt->student_id)->where('quiz_id', $attempt->quiz_id)->count();
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ $attempt->quiz->title ?? 'Unknown Quiz' }}</div>
                                        <div class="text-sm text-slate-500">{{ $attempt->quiz->description ?? 'No description.' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-indigo-100 text-indigo-700">{{ $attempt->score ?? 0 }}%</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($attempt->is_passed)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">Passed</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">Failed</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        <span class="font-semibold">Attempt #{{ $attemptNumber }}</span><br>
                                        <span class="text-xs">{{ $attemptNumber > 1 ? 'Retake' : 'First attempt' }} • Pass score {{ $attempt->quiz->passing_score ?? 0 }}%</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ optional($attempt->completed_at ?? $attempt->created_at)->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('attempts.result', $attempt) }}" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">View Result</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <p class="text-slate-500 text-lg">No scores yet.</p>
                                        <p class="text-slate-400 mt-2">Take a published quiz first, then your score will appear here.</p>
                                        <a href="{{ route('quizzes.available') }}" class="inline-block mt-4 bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700">Go to Available Quizzes</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
