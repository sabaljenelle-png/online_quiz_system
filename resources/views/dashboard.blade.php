<x-app-layout>
<div class="min-h-screen bg-slate-100 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-2xl p-6 mb-6">
            <h1 class="text-3xl font-bold text-slate-900">Welcome, {{ auth()->user()->name }}!</h1>
            <p class="text-slate-600 mt-2">Role: <strong>{{ ucfirst(auth()->user()->role) }}</strong></p>
        </div>

        @if(auth()->user()->isTeacher())
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold text-green-600">Total Quizzes</h2>
                    <p class="text-4xl font-bold mt-3">{{ $quizCount ?? 0 }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold text-blue-600">Total Questions</h2>
                    <p class="text-4xl font-bold mt-3">{{ $questionCount ?? 0 }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold text-purple-600">Total Attempts</h2>
                    <p class="text-4xl font-bold mt-3">{{ $attemptCount ?? 0 }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold mb-2">My Quizzes</h2>
                    <p class="text-slate-600 mb-4">Create, manage, publish, and view results.</p>
                    <a href="{{ route('quizzes.index') }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Manage Quizzes</a>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold mb-2">Create Quiz</h2>
                    <p class="text-slate-600 mb-4">Start a new quiz, then add questions.</p>
                    <a href="{{ route('quizzes.create') }}" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Create Quiz</a>
                </div>
            </div>
        @endif

        @if(auth()->user()->isStudent())
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold mb-2">Available Quizzes</h2>
                    <p class="text-slate-600 mb-4">Published teacher quizzes will show here.</p>
                    <a href="{{ route('quizzes.available') }}" class="inline-block bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">View Available Quizzes</a>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold mb-2">My Scores</h2>
                    <p class="text-slate-600 mb-4">Check your submitted quiz results.</p>
                    <a href="{{ route('scores.my-scores') }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">View My Scores</a>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow">
                    <h2 class="text-xl font-bold mb-2">Profile</h2>
                    <p class="text-slate-600 mb-4">Update your account information.</p>
                    <a href="{{ route('profile.edit') }}" class="inline-block bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800">Open Profile</a>
                </div>
            </div>
        @endif
    </div>
</div>
</x-app-layout>
