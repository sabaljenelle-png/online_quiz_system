<x-app-layout>
    <div class="bg-slate-100 min-h-screen py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow p-6 mb-6">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-2xl">
                        <i class="fas fa-file-circle-plus"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Create New Quiz</h1>
                        <p class="text-slate-600">Add quiz details first. After saving, you can add questions and publish it.</p>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-6">
                    <strong>Please fix these errors:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('quizzes.store') }}" method="POST" class="bg-white rounded-2xl shadow p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Quiz Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Example: Laravel Basics Quiz" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Short instruction or description for students...">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Duration (minutes)</label>
                        <input type="number" name="duration" value="{{ old('duration', 30) }}" min="1" max="480" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Passing Score (%)</label>
                        <input type="number" name="passing_score" value="{{ old('passing_score', 70) }}" min="0" max="100" class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                        <label class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-lg px-4 py-3">
                            <input type="checkbox" name="is_published" value="1" class="rounded text-indigo-600 focus:ring-indigo-500" {{ old('is_published') ? 'checked' : '' }}>
                            <span class="text-slate-700">Publish immediately</span>
                        </label>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-blue-800 text-sm">
                    Tip: If you publish immediately without questions, students still need questions to answer. Best flow: create quiz → add questions → publish.
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <a href="{{ route('quizzes.index') }}" class="px-5 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">Cancel</a>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Save Quiz</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
