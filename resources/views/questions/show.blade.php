<x-app-layout>
    <div class="py-8 max-w-3xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-4">Question Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p class="font-semibold">{{ $question->question_text }}</p>
            <p class="text-sm text-gray-600 mt-2">Type: {{ $question->question_type }} | Points: {{ $question->points }}</p>
            <h2 class="font-bold mt-4">Options</h2>
            <ul class="list-disc ml-6">
                @foreach($question->options as $option)
                    <li>{{ $option->option_text }} @if($option->is_correct) <strong>(Correct)</strong> @endif</li>
                @endforeach
            </ul>
            <a class="inline-block mt-4 text-indigo-600" href="{{ route('questions.index', $quiz) }}">Back</a>
        </div>
    </div>
</x-app-layout>
