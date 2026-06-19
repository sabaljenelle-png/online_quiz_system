<?php

namespace App\Http\Controllers;

use App\Exports\ScoresExport;
use App\Models\Quiz;
use App\Models\Score;
use App\Models\Attempt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function quizResults(Quiz $quiz)
    {
        $quiz->load(['attempts.student', 'questions.options']);
        return view('reports.scores', compact('quiz'));
    }

    public function analytics(Quiz $quiz)
    {
        $quiz->loadCount(['questions', 'attempts']);
        $averageScore = round((float) $quiz->attempts()->avg('score'), 2);
        $passedCount = $quiz->attempts()->where('is_passed', true)->count();
        $failedCount = $quiz->attempts()->where('is_passed', false)->count();

        return view('reports.analytics', compact('quiz', 'averageScore', 'passedCount', 'failedCount'));
    }

    public function exportQuizResultsPDF(Quiz $quiz)
    {
        $quiz->load(['attempts.student', 'questions.options']);
        $pdf = Pdf::loadView('reports.scores', compact('quiz'));
        return $pdf->download('quiz-' . $quiz->id . '-results.pdf');
    }

    public function exportQuizResultsExcel(Quiz $quiz)
    {
        return Excel::download(new ScoresExport($quiz->id), 'quiz-' . $quiz->id . '-results.xlsx');
    }

    public function exportQuizResultsCSV(Quiz $quiz)
    {
        return Excel::download(new ScoresExport($quiz->id), 'quiz-' . $quiz->id . '-results.csv');
    }

    public function exportPDF()
    {
        $scores = Score::with(['attempt.student', 'attempt.quiz', 'question'])->get();
        $pdf = Pdf::loadView('reports.all-scores', compact('scores'));
        return $pdf->download('scores-report.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ScoresExport(), 'scores.xlsx');
    }

    public function exportCSV()
    {
        return Excel::download(new ScoresExport(), 'scores.csv');
    }

    public function allReports()
    {
        $quizzes = Quiz::withCount(['questions', 'attempts'])->latest()->get();
        return view('reports.index', compact('quizzes'));
    }

    public function exportAllResultsPDF()
    {
        $quizzes = Quiz::with(['attempts.student'])->get();
        $pdf = Pdf::loadView('reports.all-results', compact('quizzes'));
        return $pdf->download('all-quiz-results.pdf');
    }

    public function index(Request $request)
    {
        $query = Quiz::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $quizzes = $query->paginate(10);
        return view('quizzes.index', compact('quizzes'));
    }
}
