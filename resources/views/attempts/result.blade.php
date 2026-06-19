<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Timer -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold text-gray-900">{{ $attempt->quiz->title }}</h1>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600" id="timer">{{ $timeRemaining }}:00</div>
                    <p class="text-gray-600">Time Remaining</p>
                </div>
            </div>
        </div>

        <!-- Questions Form -->
        <form action="{{ route('attempts.submit', $attempt) }}" method="POST" class="space-y-6">
            @csrf

            @foreach ($attempt->quiz->questions as $index => $question)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Question {{ $index + 1 }}: {{ $question->question_text }}
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $question->points }} points</p>
                    </div>

                    @if ($question->question_type === 'multiple_choice' || $question->question_type === 'true_false')
                        <div class="space-y-2">
                            @foreach ($question->options as $option)
                                <label class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                        class="mr-3" required>
                                    <span>{{ $option->option_text }}</span>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <textarea name="answers[{{ $question->id }}]" rows="4" placeholder="Enter your answer..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" required></textarea>
                    @endif
                </div>
            @endforeach

            <!-- Submit Button -->
            <div class="flex gap-4">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-semibold">
                    Submit Quiz
                </button>
            </div>
        </form>
    </div>

    <script>
        // Timer countdown
        let timeRemaining = {{ $timeRemaining }};
        const timerElement = document.getElementById('timer');

        setInterval(function() {
            if (timeRemaining > 0) {
                timeRemaining--;
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                timerElement.textContent = minutes + ':' + (seconds < 10 ? '0' : '') + seconds;

                if (timeRemaining === 0) {
                    document.querySelector('form').submit();
                }
            }
        }, 1000);
    </script>
</x-app-layout>