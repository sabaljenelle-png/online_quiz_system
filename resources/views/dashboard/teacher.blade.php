<x-app-layout>

<div class="grid grid-cols-3 gap-6">

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold">Total Quizzes</h2>
        <p class="text-4xl font-bold text-blue-600">
            {{ $quizCount }}
        </p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold">Questions</h2>
        <p class="text-4xl font-bold text-green-600">
            {{ $questionCount }}
        </p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg font-semibold">Attempts</h2>
        <p class="text-4xl font-bold text-purple-600">
            {{ $attemptCount }}
        </p>
    </div>

</div>

</x-app-layout>