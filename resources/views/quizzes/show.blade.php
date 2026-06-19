<x-app-layout>
    <div class="bg-slate-100 min-h-screen py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs px-2 py-1 rounded-full {{ $quiz->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $quiz->is_published ? 'Published' : 'Draft' }}
                            </span>
                            <span class="text-sm text-slate-500">Created by {{ $quiz->teacher->name ?? 'Teacher' }}</span>
                        </div>
                        <h1 class="text-3xl font-bold text-slate-900">{{ $quiz->title }}</h1>
                        <p class="text-slate-600 mt-3">{{ $quiz->description ?: 'No description.' }}</p>
                    </div>

                    @if(auth()->user()->isTeacher() && $quiz->teacher_id === auth()->id())
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('quizzes.index') }}" class="bg-slate-200 text-slate-800 px-4 py-2 rounded-lg hover:bg-slate-300">Back to Quizzes</a>
                            <a href="{{ route('quizzes.edit', $quiz) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Edit</a>
                            <a href="{{ route('questions.create', $quiz) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Add Question</a>
                            @if($quiz->is_published)
                                <form action="{{ route('quizzes.unpublish', $quiz) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800">Unpublish</button>
                                </form>
                            @else
                                <form action="{{ route('quizzes.publish', $quiz) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Publish</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center mt-6">
                    <div class="bg-slate-50 p-4 rounded-xl"><div class="text-2xl font-bold text-indigo-600">{{ $quiz->questions->count() }}</div><div class="text-sm text-slate-600">Questions</div></div>
                    <div class="bg-slate-50 p-4 rounded-xl"><div class="text-2xl font-bold text-indigo-600">{{ $quiz->duration }}</div><div class="text-sm text-slate-600">Minutes</div></div>
                    <div class="bg-slate-50 p-4 rounded-xl"><div class="text-2xl font-bold text-indigo-600">{{ $quiz->passing_score }}%</div><div class="text-sm text-slate-600">Pass Score</div></div>
                    <div class="bg-slate-50 p-4 rounded-xl"><div class="text-2xl font-bold text-indigo-600">{{ $quiz->attempts->count() }}</div><div class="text-sm text-slate-600">Attempts</div></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-slate-900">Questions</h2>
                    @if(auth()->user()->isTeacher() && $quiz->teacher_id === auth()->id())
                        <a href="{{ url('/quizzes/' . $quiz->id . '/questions') }}" class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-semibold shadow-sm">Manage Questions</a>
                    @endif
                </div>

                @forelse($quiz->questions as $question)
                    <div class="border border-slate-200 rounded-xl p-4 mb-4">
                        <div class="flex justify-between gap-4">
                            <div class="flex-1">
                                <p class="font-semibold text-slate-900">{{ $loop->iteration }}. {{ $question->question_text }}</p>
                                <p class="text-sm text-slate-500 mt-1">{{ str_replace('_', ' ', ucfirst($question->question_type ?? $question->type)) }}{{ ($question->question_type ?? $question->type) === 'multiple_choice' ? ' • ' . ucfirst($question->answer_mode ?? 'radio') : '' }} • {{ $question->points }} point(s)</p>
                                <div class="mt-3 grid gap-2">
                                    @foreach($question->options as $option)
                                        <div class="text-sm {{ $option->is_correct ? 'text-green-700 font-semibold' : 'text-slate-600' }}">
                                            {{ $option->is_correct ? '✓' : '○' }} {{ $option->option_text }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-500">No questions added yet.</div>
                @endforelse
            </div>

            @if(auth()->user()->isTeacher() && $quiz->teacher_id === auth()->id())
                <div class="bg-white rounded-2xl shadow p-6">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">Student Attempts</h2>
                    @if($quiz->attempts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Student</th>
                                        <th class="px-4 py-3 text-left">Score</th>
                                        <th class="px-4 py-3 text-left">Status</th>
                                        <th class="px-4 py-3 text-left">Completed</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @foreach($quiz->attempts as $attempt)
                                        <tr>
                                            <td class="px-4 py-3">{{ $attempt->student->name ?? 'Student' }}</td>
                                            <td class="px-4 py-3">{{ $attempt->score ?? 0 }}%</td>
                                            <td class="px-4 py-3">{{ ucfirst($attempt->status) }}</td>
                                            <td class="px-4 py-3">{{ $attempt->completed_at?->format('M d, Y h:i A') ?? 'Not completed' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-slate-500">No student attempts yet.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
