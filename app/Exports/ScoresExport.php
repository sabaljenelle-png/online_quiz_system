<?php

namespace App\Exports;

use App\Models\Attempt;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScoresExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private ?int $quizId = null)
    {
    }

    public function collection()
    {
        return Attempt::with(['student', 'quiz'])
            ->when($this->quizId, fn ($query) => $query->where('quiz_id', $this->quizId))
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['Student', 'Email', 'Quiz', 'Score', 'Passed', 'Status', 'Attempt', 'Passing Score', 'Started At', 'Completed At'];
    }

    public function map($attempt): array
    {
        $attemptNumber = Attempt::where('student_id', $attempt->student_id)
            ->where('quiz_id', $attempt->quiz_id)
            ->where('id', '<=', $attempt->id)
            ->count();

        $totalAttempts = Attempt::where('student_id', $attempt->student_id)
            ->where('quiz_id', $attempt->quiz_id)
            ->count();

        return [
            $attempt->student?->name,
            $attempt->student?->email,
            $attempt->quiz?->title,
            $attempt->score,
            $attempt->is_passed ? 'Yes' : 'No',
            $attempt->status,
            'Attempt #' . $attemptNumber . ($attemptNumber > 1 ? ' (Retake)' : ' (First)'),
            ($attempt->quiz?->passing_score ?? 0) . '%',
            optional($attempt->started_at)->format('Y-m-d H:i:s'),
            optional($attempt->completed_at)->format('Y-m-d H:i:s'),
        ];
    }
}
