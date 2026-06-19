<?php

namespace App\Imports;

use App\Models\Quiz;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuizzesImport implements ToModel, WithHeadingRow
{
    public function __construct(private int $teacherId = 1)
    {
    }

    public function model(array $row)
    {
        if (empty($row['title'])) {
            return null;
        }

        return new Quiz([
            'teacher_id' => $this->teacherId,
            'title' => $row['title'],
            'description' => $row['description'] ?? null,
            'duration' => (int) ($row['duration'] ?? 60),
            'passing_score' => (int) ($row['passing_score'] ?? 70),
            'is_published' => filter_var($row['is_published'] ?? false, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
