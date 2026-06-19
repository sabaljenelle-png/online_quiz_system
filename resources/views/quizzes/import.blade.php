<x-app-layout>
    <div class="bg-slate-100 min-h-screen py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">Import Quizzes</h1>
                        <p class="mt-2 text-sm text-slate-600">Upload CSV/XLSX with these columns:</p>
                        <p class="mt-1 text-xs bg-slate-100 text-slate-700 rounded px-3 py-2 font-mono">title, description, duration, passing_score, is_published, question_text, question_type, answer_mode, option_1, option_2, option_3, option_4, correct_option, correct_options</p>
                    </div>
                    <a href="{{ route('quizzes.index') }}" class="bg-slate-200 text-slate-800 px-4 py-2 rounded-lg hover:bg-slate-300 text-center">Back to Quizzes</a>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 text-red-700 p-4 text-sm">
                        <strong>Import failed:</strong>
                        <ul class="list-disc pl-5 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-5 rounded-lg border border-amber-200 bg-amber-50 text-amber-800 p-4 text-sm">
                    Use the sample file if you are not sure about the format. After importing, the new quizzes and their questions will appear in <strong>My Quizzes</strong>.
                </div>

                <form method="POST" action="{{ route('quizzes.import') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Select CSV/XLSX file</label>
                        <input type="file" name="file" required accept=".csv,.txt,.xlsx" class="block w-full border border-slate-300 rounded-lg p-3 bg-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <button class="bg-indigo-600 text-white px-5 py-2 rounded-lg hover:bg-indigo-700" type="submit">Import</button>
                        <a href="{{ route('quizzes.import-sample') }}" class="bg-amber-500 text-white px-5 py-2 rounded-lg hover:bg-amber-600">Download Sample</a>
                        <a href="{{ route('quizzes.index') }}" class="bg-slate-200 text-slate-800 px-5 py-2 rounded-lg hover:bg-slate-300">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
