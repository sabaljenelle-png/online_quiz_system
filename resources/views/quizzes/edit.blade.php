<x-app-layout>
    <div class="py-8 max-w-3xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">Edit Quiz</h1>
        <form method="POST" action="{{ route('quizzes.update', $quiz) }}" class="bg-white p-6 rounded shadow space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold mb-1">Title</label>
                <input class="w-full border rounded p-2" type="text" name="title" value="{{ old('title', $quiz->title) }}" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Description</label>
                <textarea class="w-full border rounded p-2" name="description">{{ old('description', $quiz->description) }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Duration</label>
                    <input class="w-full border rounded p-2" type="number" name="duration" value="{{ old('duration', $quiz->duration) }}" required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Passing Score</label>
                    <input class="w-full border rounded p-2" type="number" name="passing_score" value="{{ old('passing_score', $quiz->passing_score) }}" min="0" max="100">
                </div>
            </div>
            <label class="flex gap-2 items-center">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $quiz->is_published))>
                Published
            </label>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded" type="submit">Update Quiz</button>
            <a class="ml-2 text-gray-600" href="{{ route('quizzes.show', $quiz) }}">Cancel</a>
        </form>
    </div>
</x-app-layout>
