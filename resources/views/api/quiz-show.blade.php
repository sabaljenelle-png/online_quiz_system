@extends('layouts.app')

@section('content')
<div class="bg-slate-100 min-h-screen py-10">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">API Preview</p>
            <h1 class="text-3xl font-bold text-slate-900 mt-1">{{ $quiz->title }}</h1>
            <p class="text-slate-600 mt-2">GET /api/v1/quizzes/{{ $quiz->id }}</p>
            <p class="text-slate-700 mt-4">{{ $quiz->description ?: 'No description.' }}</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 text-center">
                <div class="bg-slate-50 rounded-xl p-4"><strong class="text-2xl text-indigo-600">{{ $quiz->questions->count() }}</strong><br><span class="text-sm text-slate-600">Questions</span></div>
                <div class="bg-slate-50 rounded-xl p-4"><strong class="text-2xl text-indigo-600">{{ $quiz->duration }}</strong><br><span class="text-sm text-slate-600">Minutes</span></div>
                <div class="bg-slate-50 rounded-xl p-4"><strong class="text-2xl text-indigo-600">{{ $quiz->passing_score }}%</strong><br><span class="text-sm text-slate-600">Pass Score</span></div>
                <div class="bg-slate-50 rounded-xl p-4"><strong class="text-2xl text-indigo-600">{{ $quiz->attempts->count() }}</strong><br><span class="text-sm text-slate-600">Attempts</span></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Questions</h2>
            <div class="space-y-4">
                @forelse($quiz->questions as $question)
                    <div class="border border-slate-200 rounded-xl p-4">
                        <p class="font-semibold text-slate-900">{{ $loop->iteration }}. {{ $question->question_text }}</p>
                        <div class="mt-3 space-y-2">
                            @foreach($question->options as $option)
                                <div class="text-sm {{ $option->is_correct ? 'text-green-700 font-semibold' : 'text-slate-600' }}">
                                    {{ $option->is_correct ? '✓' : '○' }} {{ $option->option_text }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <p class="text-slate-500">No questions yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
