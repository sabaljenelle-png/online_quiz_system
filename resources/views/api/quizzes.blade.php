@extends('layouts.app')

@section('content')
<div class="bg-slate-100 min-h-screen py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-indigo-600 uppercase tracking-wide">API Preview</p>
                    <h1 class="text-3xl font-bold text-slate-900">GET /api/v1/quizzes</h1>
                    <p class="text-slate-600 mt-2">This page is browser-friendly. In Postman, add <code class="bg-slate-100 px-2 py-1 rounded">Accept: application/json</code> to receive JSON.</p>
                </div>
                <div class="bg-slate-900 text-white rounded-xl px-4 py-3 text-sm">
                    REST API: GET, POST, PUT/PATCH, DELETE
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($quizzes as $quiz)
                <div class="bg-white rounded-2xl shadow p-6 border border-slate-200">
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="text-xl font-bold text-slate-900">{{ $quiz->title }}</h2>
                        <span class="text-xs px-2 py-1 rounded-full {{ $quiz->is_published ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ $quiz->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>
                    <p class="text-slate-600 mt-3 min-h-12">{{ $quiz->description ?: 'No description.' }}</p>
                    <div class="grid grid-cols-3 gap-3 mt-5 text-center text-sm">
                        <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $quiz->questions_count }}</strong><br>Questions</div>
                        <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $quiz->duration }}</strong><br>Minutes</div>
                        <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $quiz->passing_score }}%</strong><br>Pass</div>
                    </div>
                    <a href="/api/v1/quizzes/{{ $quiz->id }}" class="block mt-5 text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">View API Preview</a>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-2xl shadow p-10 text-center text-slate-500">No quizzes found.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $quizzes->links() }}</div>
    </div>
</div>
@endsection
