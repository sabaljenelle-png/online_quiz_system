<x-app-layout>
    <div class="py-8 max-w-6xl mx-auto px-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
            <h1 class="text-2xl font-bold">Quiz Results: {{ $quiz->title }}</h1>
            <a class="border border-slate-300 text-slate-700 px-4 py-2 rounded hover:bg-slate-50" href="{{ url('/quizzes') }}">← Back to Quizzes</a>
        </div>
        <div class="mb-4 flex flex-wrap gap-2">
            <a class="bg-red-600 text-white px-3 py-2 rounded" href="{{ route('reports.quiz-pdf', $quiz) }}">Export PDF</a>
            <a class="bg-green-600 text-white px-3 py-2 rounded" href="{{ route('reports.quiz-excel', $quiz) }}">Export XLSX</a>
            <a class="bg-blue-600 text-white px-3 py-2 rounded" href="{{ route('reports.quiz-csv', $quiz) }}">Export CSV</a>
        </div>
        <table class="w-full border bg-white">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2 text-left">Student</th>
                    <th class="border p-2 text-left">Email</th>
                    <th class="border p-2 text-left">Score</th>
                    <th class="border p-2 text-left">Passed</th>
                    <th class="border p-2 text-left">Status</th>
                    <th class="border p-2 text-left">Attempt</th>
                    <th class="border p-2 text-left">Passing Score</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quiz->attempts as $attempt)
                    @php
                        $attemptNumber = \App\Models\Attempt::where('student_id', $attempt->student_id)->where('quiz_id', $attempt->quiz_id)->where('id', '<=', $attempt->id)->count();
                        $totalAttempts = \App\Models\Attempt::where('student_id', $attempt->student_id)->where('quiz_id', $attempt->quiz_id)->count();
                    @endphp
                    <tr>
                        <td class="border p-2">{{ $attempt->student?->name }}</td>
                        <td class="border p-2">{{ $attempt->student?->email }}</td>
                        <td class="border p-2">{{ $attempt->score ?? 0 }}%</td>
                        <td class="border p-2">{{ $attempt->is_passed ? 'Yes' : 'No' }}</td>
                        <td class="border p-2">{{ ucfirst(str_replace('_', ' ', $attempt->status)) }}</td>
                        <td class="border p-2">Attempt #{{ $attemptNumber }} {{ $attemptNumber > 1 ? '(Retake)' : '(First)'  }}</td>
                        <td class="border p-2">{{ $quiz->passing_score }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="border p-2 text-center">No attempts yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
