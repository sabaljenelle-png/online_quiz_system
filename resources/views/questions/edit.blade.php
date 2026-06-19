<x-app-layout>
    <div class="bg-slate-100 min-h-screen py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">Edit Question</h1>
                        <p class="text-slate-600 mt-1">Quiz: <span class="font-semibold">{{ $quiz->title }}</span></p>
                    </div>
                    <a href="{{ url('/quizzes/' . $quiz->id . '/questions') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">← Back to Manage Questions</a>
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

            @php
                $questionType = old('question_type', $question->question_type ?? $question->type ?? 'multiple_choice');
                $answerMode = old('answer_mode', $question->answer_mode ?? 'radio');
                $existingOptions = $question->options->values();
                $oldOptions = old('options');
                $optionTexts = is_array($oldOptions) ? $oldOptions : $existingOptions->pluck('option_text')->toArray();
                while (count($optionTexts) < 2) { $optionTexts[] = ''; }
                $correctIndex = old('correct_option');
                if ($correctIndex === null) {
                    $foundIndex = $existingOptions->search(fn($option) => $option->is_correct);
                    $correctIndex = $foundIndex !== false ? $foundIndex : 0;
                }
                $correctOptions = old('correct_options');
                if (! is_array($correctOptions)) {
                    $correctOptions = $existingOptions->filter(fn($option) => $option->is_correct)->keys()->map(fn($key) => (string) $key)->values()->all();
                } else {
                    $correctOptions = collect($correctOptions)->map(fn($v) => (string) $v)->all();
                }
                $trueFalseAnswer = old('true_false_answer', optional($existingOptions->firstWhere('is_correct', true))->option_text === 'False' ? 'false' : 'true');
            @endphp

            <form method="POST" action="{{ route('questions.update.post', [$quiz, $question]) }}" class="bg-white rounded-2xl shadow p-6 space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Question Text</label>
                    <textarea class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" name="question_text" rows="4" required>{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Question Type</label>
                        <select class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" name="question_type" id="question_type" onchange="toggleQuestionType()">
                            <option value="multiple_choice" {{ $questionType === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="true_false" {{ $questionType === 'true_false' ? 'selected' : '' }}>True / False</option>
                        </select>
                    </div>
                    <div id="answer_mode_box">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Answer Style</label>
                        <select class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" name="answer_mode" id="answer_mode" onchange="toggleAnswerMode()">
                            <option value="radio" {{ $answerMode === 'radio' ? 'selected' : '' }}>One Answer</option>
                            <option value="checkbox" {{ $answerMode === 'checkbox' ? 'selected' : '' }}>Multiple Answers</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Points</label>
                        <input class="w-full border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" type="number" name="points" value="{{ old('points', $question->points) }}" min="1" max="100" required>
                    </div>
                </div>

                <div id="multiple_choice_box" class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-blue-800 text-sm">
                        <p><strong>One Answer</strong> means the student can choose only one option.</p>
                        <p><strong>Multiple Answers</strong> means the student can choose two or more options.</p>
                        <p class="mt-1">Use the circle/checkbox beside each answer to set the correct answer. Selected correct answers turn green.</p>
                    </div>

                    <div id="options_container" class="space-y-3">
                        @foreach ($optionTexts as $i => $optionText)
                            <div class="option-row flex flex-col md:flex-row md:items-center gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50">
                                <input type="radio" name="correct_option" value="{{ $i }}" class="correct-radio text-indigo-600 focus:ring-indigo-500" {{ (string) $correctIndex === (string) $i ? 'checked' : '' }}>
                                <input type="checkbox" name="correct_options[]" value="{{ $i }}" class="correct-checkbox hidden rounded text-green-600 focus:ring-green-500" {{ in_array((string) $i, $correctOptions, true) ? 'checked' : '' }}>
                                <input type="text" name="options[]" value="{{ $optionText }}" class="option-input flex-1 border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Answer choice {{ $i + 1 }}{{ $i < 2 ? ' (required)' : '' }}" {{ $i < 2 ? 'required' : '' }}>
                                <button type="button" onclick="removeOption(this)" class="remove-option px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 text-sm font-semibold {{ $i < 2 ? 'hidden' : '' }}">Remove</button>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pt-2">
                        <p class="text-sm text-slate-500">Add another answer choice if this question needs more than the existing choices.</p>
                        <button type="button" onclick="addOption()" class="bg-slate-800 text-white px-4 py-2 rounded-lg hover:bg-slate-900 font-semibold">+ Add Option</button>
                    </div>
                </div>

                <div id="true_false_box" class="hidden space-y-3">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-blue-800 text-sm">
                        The system will automatically create two choices: True and False.
                    </div>
                    <label class="flex items-center gap-3 border border-slate-200 rounded-lg p-3">
                        <input type="radio" name="true_false_answer" value="true" class="text-indigo-600 focus:ring-indigo-500" {{ $trueFalseAnswer === 'true' ? 'checked' : '' }}>
                        <span>Correct answer is True</span>
                    </label>
                    <label class="flex items-center gap-3 border border-slate-200 rounded-lg p-3">
                        <input type="radio" name="true_false_answer" value="false" class="text-indigo-600 focus:ring-indigo-500" {{ $trueFalseAnswer === 'false' ? 'checked' : '' }}>
                        <span>Correct answer is False</span>
                    </label>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700" type="submit">Update Question</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleQuestionType() {
            const type = document.getElementById('question_type').value;
            const isMultiple = type === 'multiple_choice';
            document.getElementById('multiple_choice_box').classList.toggle('hidden', !isMultiple);
            document.getElementById('true_false_box').classList.toggle('hidden', isMultiple);
            document.getElementById('answer_mode_box').classList.toggle('hidden', !isMultiple);
            document.querySelectorAll('#multiple_choice_box input, #multiple_choice_box button, #answer_mode').forEach(input => input.disabled = !isMultiple);
            document.querySelectorAll('#true_false_box input').forEach(input => input.disabled = isMultiple);
            toggleAnswerMode();
        }

        function toggleAnswerMode() {
            const mode = document.getElementById('answer_mode').value;
            const isCheckbox = mode === 'checkbox';
            document.querySelectorAll('.correct-radio').forEach(el => el.classList.toggle('hidden', isCheckbox));
            document.querySelectorAll('.correct-checkbox').forEach(el => el.classList.toggle('hidden', !isCheckbox));
            updateOptionStyles();
        }

        function refreshOptionIndexes() {
            document.querySelectorAll('#options_container .option-row').forEach((row, index) => {
                row.querySelector('.correct-radio').value = index;
                row.querySelector('.correct-checkbox').value = index;
                const input = row.querySelector('.option-input');
                input.placeholder = `Answer choice ${index + 1}${index < 2 ? ' (required)' : ''}`;
                input.required = index < 2;
                row.querySelector('.remove-option').classList.toggle('hidden', index < 2);
            });
            updateOptionStyles();
        }

        function updateOptionStyles() {
            document.querySelectorAll('#options_container .option-row').forEach(row => {
                const radio = row.querySelector('.correct-radio');
                const checkbox = row.querySelector('.correct-checkbox');
                const selected = (radio && radio.checked && !radio.classList.contains('hidden')) ||
                    (checkbox && checkbox.checked && !checkbox.classList.contains('hidden'));
                row.classList.toggle('border-green-400', selected);
                row.classList.toggle('bg-green-50', selected);
                row.classList.toggle('ring-2', selected);
                row.classList.toggle('ring-green-100', selected);
            });
        }

        document.addEventListener('change', function (event) {
            if (event.target.classList.contains('correct-radio') || event.target.classList.contains('correct-checkbox')) {
                updateOptionStyles();
            }
        });

        function removeOption(button) {
            const rows = document.querySelectorAll('#options_container .option-row');
            if (rows.length <= 2) {
                alert('At least two choices are required.');
                return;
            }
            const row = button.closest('.option-row');
            const wasChecked = row.querySelector('.correct-radio').checked;
            row.remove();
            refreshOptionIndexes();
            if (wasChecked) {
                const firstRadio = document.querySelector('#options_container .correct-radio');
                if (firstRadio) firstRadio.checked = true;
            }
            toggleAnswerMode();
        }

        function addOption() {
            const container = document.getElementById('options_container');
            const index = container.querySelectorAll('.option-row').length;
            const row = document.createElement('div');
            row.className = 'option-row flex flex-col md:flex-row md:items-center gap-3 p-3 rounded-xl border border-slate-200 bg-slate-50';
            row.innerHTML = `
                <input type="radio" name="correct_option" value="${index}" class="correct-radio text-indigo-600 focus:ring-indigo-500">
                <input type="checkbox" name="correct_options[]" value="${index}" class="correct-checkbox hidden rounded text-green-600 focus:ring-green-500">
                <input type="text" name="options[]" class="option-input flex-1 border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Answer choice ${index + 1}">
                <button type="button" onclick="removeOption(this)" class="remove-option px-3 py-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 text-sm font-semibold">Remove</button>
            `;
            container.appendChild(row);
            refreshOptionIndexes();
            toggleAnswerMode();
        }

        document.addEventListener('DOMContentLoaded', updateOptionStyles);
        toggleQuestionType();
        refreshOptionIndexes();
    </script>
</x-app-layout>
