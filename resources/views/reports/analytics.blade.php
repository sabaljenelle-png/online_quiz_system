<x-app-layout>
    <div class="py-8 max-w-5xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">Analytics: {{ $quiz->title }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded shadow">Questions: <strong>{{ $quiz->questions_count }}</strong></div>
            <div class="bg-white p-4 rounded shadow">Attempts: <strong>{{ $quiz->attempts_count }}</strong></div>
            <div class="bg-white p-4 rounded shadow">Average: <strong>{{ $averageScore }}%</strong></div>
            <div class="bg-white p-4 rounded shadow">Passed/Failed: <strong>{{ $passedCount }}/{{ $failedCount }}</strong></div>
        </div>
    </div>
</x-app-layout>
