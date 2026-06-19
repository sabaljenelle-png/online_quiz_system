<x-app-layout>
    <div class="bg-slate-100 min-h-screen py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $durationMinutes = max(1, (int) round((float) $quiz->duration));
                $durationSeconds = $durationMinutes * 60;
                $elapsedSeconds = $attempt->started_at ? (int) floor($attempt->started_at->diffInSeconds(now())) : 0;
                $remainingSeconds = max(0, (int) ($durationSeconds - $elapsedSeconds));
            @endphp

            <div class="bg-white rounded-2xl shadow p-6 mb-6 sticky top-4 z-20 border border-slate-200">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">{{ $quiz->title }}</h1>
                        <p class="text-slate-600 mt-2">{{ $quiz->description ?: 'Answer all questions, then submit your quiz.' }}</p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-center text-sm min-w-[320px]">
                        <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $quiz->questions->count() }}</strong><br>Questions</div>
                        <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $durationMinutes }}</strong><br>Minutes</div>
                        <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $quiz->passing_score }}%</strong><br>Pass</div>
                        <div id="timerCard" class="bg-indigo-50 rounded-lg p-3 border border-indigo-200">
                            <strong id="quizTimer" class="text-indigo-700 text-lg">--:--</strong><br>Time Left
                        </div>
                    </div>
                </div>
            </div>

            @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-6">{{ session('error') }}</div>
            @endif

            @if($quiz->questions->count() === 0)
                <div class="bg-white rounded-2xl shadow p-10 text-center text-slate-500">This quiz has no questions yet.</div>
            @else
                <form id="quizForm" action="{{ route('attempts.complete', $attempt) }}" method="POST" class="space-y-5" onsubmit="return validateCheckboxQuestions()">
                    @csrf
                    @foreach($quiz->questions as $question)
                        <div class="bg-white rounded-2xl shadow p-6" data-question-id="{{ $question->id }}" data-checkbox-question="{{ (($question->question_type ?? $question->type) === 'multiple_choice' && ($question->answer_mode ?? 'radio') === 'checkbox') ? '1' : '0' }}">
                            <h3 class="font-bold text-slate-900 mb-4">{{ $loop->iteration }}. {{ $question->question_text }}</h3>

                            @if($question->options->count() === 0)
                                <p class="text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">This question has no answer choices yet. Ask the teacher to update it.</p>
                            @else
                                <div class="space-y-3">
                                    @php
                                        $isCheckboxQuestion = ($question->question_type ?? $question->type) === 'multiple_choice' && ($question->answer_mode ?? 'radio') === 'checkbox';
                                        $oldAnswer = old('answers.' . $question->id, []);
                                        $oldAnswerArray = is_array($oldAnswer) ? $oldAnswer : [$oldAnswer];
                                    @endphp
                                    @if($isCheckboxQuestion)
                                        <p class="text-sm text-blue-700 bg-blue-50 border border-blue-200 rounded-lg p-3 mb-2">Select all correct answers. This question uses checkboxes.</p>
                                    @endif
                                    @foreach($question->options as $option)
                                        <label class="flex items-center gap-3 border border-slate-200 rounded-lg p-3 hover:bg-slate-50 cursor-pointer">
                                            @if($isCheckboxQuestion)
                                                <input type="checkbox" name="answers[{{ $question->id }}][]" value="{{ $option->id }}" class="rounded text-indigo-600 focus:ring-indigo-500" {{ in_array($option->id, array_map('intval', $oldAnswerArray)) ? 'checked' : '' }}>
                                            @else
                                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" class="text-indigo-600 focus:ring-indigo-500" {{ old('answers.' . $question->id) == $option->id ? 'checked' : '' }} required>
                                            @endif
                                            <span>{{ $option->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <div class="bg-white rounded-2xl shadow p-6 flex justify-end gap-3">
                        <a href="{{ route('quizzes.available') }}" class="px-5 py-2 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50">Cancel</a>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700" onclick="return confirm('Submit this quiz now?')">Submit Quiz</button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script>
        let remaining = {{ (int) ($remainingSeconds ?? 0) }};
        const timer = document.getElementById('quizTimer');
        const timerCard = document.getElementById('timerCard');
        const form = document.getElementById('quizForm');

        function formatTime(seconds) {
            seconds = Math.max(0, Math.floor(Number(seconds) || 0));
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }

        function validateCheckboxQuestions() {
            const checkboxCards = document.querySelectorAll('[data-checkbox-question="1"]');
            for (const card of checkboxCards) {
                const checked = card.querySelectorAll('input[type="checkbox"]:checked').length;
                if (checked === 0) {
                    alert('Please select at least one answer for each checkbox question.');
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }
            }
            return true;
        }

        function tickTimer() {
            if (!timer) return;
            timer.textContent = formatTime(Math.max(remaining, 0));

            if (remaining <= 60 && timerCard) {
                timerCard.classList.remove('bg-indigo-50', 'border-indigo-200');
                timerCard.classList.add('bg-red-50', 'border-red-300');
                timer.classList.remove('text-indigo-700');
                timer.classList.add('text-red-700');
            }

            if (remaining <= 0) {
                if (form) {
                    alert('Time is up. Your quiz will be submitted now.');
                    form.submit();
                }
                return;
            }

            remaining--;
            setTimeout(tickTimer, 1000);
        }

        tickTimer();
    </script>
</x-app-layout>
