<x-app-layout>
    <div class="bg-slate-100 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900">My Quizzes</h1>
                        <p class="text-slate-600 mt-1">Create quiz → add/manage questions → publish → students can take it.</p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('quizzes.import-form') }}" class="bg-slate-700 text-white px-4 py-2 rounded-lg hover:bg-slate-800">Import Quiz</a>
                        <a href="{{ route('quizzes.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Create New Quiz</a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow p-4 mb-6">
                <form method="GET" action="{{ route('quizzes.index') }}" class="flex gap-3">
                    <input type="text" name="search" placeholder="Search quizzes..." value="{{ request('search') }}" class="flex-1 border-slate-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700">Search</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($quizzes as $quiz)
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow hover:shadow-lg transition flex flex-col">
                        <div class="flex items-start justify-between gap-3">
                            <h3 class="text-xl font-bold text-slate-900">{{ $quiz->title }}</h3>
                            <span class="text-xs px-2 py-1 rounded-full {{ $quiz->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $quiz->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </div>

                        <p class="text-slate-600 mt-3 min-h-12 flex-1">{{ Str::limit($quiz->description ?: 'No description.', 100) }}</p>

                        <div class="grid grid-cols-3 gap-2 mt-5 text-center text-sm">
                            <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $quiz->questions_count ?? $quiz->questions()->count() }}</strong><br>Questions</div>
                            <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $quiz->duration }}</strong><br>Minutes</div>
                            <div class="bg-slate-50 rounded-lg p-3"><strong>{{ $quiz->passing_score }}%</strong><br>Pass</div>
                        </div>

                        <div class="mt-5 grid grid-cols-2 gap-2">
                            <a href="{{ route('quizzes.show', $quiz) }}" class="bg-indigo-600 text-white text-center px-3 py-2 rounded-lg hover:bg-indigo-700">View</a>
                            <a href="{{ route('quizzes.results', $quiz) }}" class="bg-purple-600 text-white text-center px-3 py-2 rounded-lg hover:bg-purple-700">Results</a>
                        </div>

                        <div class="mt-3 flex gap-2">
                            @if($quiz->is_published)
                                <form action="{{ route('quizzes.unpublish', $quiz) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-slate-700 text-white px-3 py-2 rounded-lg hover:bg-slate-800">Unpublish</button>
                                </form>
                            @else
                                <form action="{{ route('quizzes.publish', $quiz) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full bg-orange-600 text-white px-3 py-2 rounded-lg hover:bg-orange-700">Publish</button>
                                </form>
                            @endif
                            <form action="{{ route('quizzes.destroy', $quiz) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this quiz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl shadow p-10 text-center">
                        <p class="text-slate-500 text-lg">No quizzes found.</p>
                        <a href="{{ route('quizzes.create') }}" class="inline-block mt-4 bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700">Create your first quiz</a>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">{{ method_exists($quizzes, 'links') ? $quizzes->links() : '' }}</div>
        </div>
    </div>
</x-app-layout>
