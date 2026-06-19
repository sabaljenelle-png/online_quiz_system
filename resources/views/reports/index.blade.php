<x-app-layout>
    <div class="py-8 max-w-6xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">All Reports</h1>
        <table class="w-full border bg-white">
            <thead><tr class="bg-gray-100"><th class="border p-2">Quiz</th><th class="border p-2">Questions</th><th class="border p-2">Attempts</th></tr></thead>
            <tbody>
                @foreach($quizzes as $quiz)
                    <tr><td class="border p-2">{{ $quiz->title }}</td><td class="border p-2">{{ $quiz->questions_count }}</td><td class="border p-2">{{ $quiz->attempts_count }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
