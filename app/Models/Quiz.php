<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'title',
        'description',
        'user_id',
        'duration',
        'passing_score',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'duration' => 'integer',
        'passing_score' => 'integer',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the teacher who created this quiz.
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get all questions for this quiz.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get all attempts for this quiz.
     */
    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    /**
     * Get all scores for this quiz.
     */
    public function scores()
    {
        return $this->hasManyThrough(Score::class, Attempt::class, 'quiz_id', 'attempt_id');
    }

    // ===== HELPER METHODS =====

    /**
     * Get question count.
     */
    public function questionCount()
    {
        return $this->questions()->count();
    }

    /**
     * Get average score for this quiz.
     */
    public function averageScore()
    {
        return $this->scores()
            ->where('is_correct', true)
            ->avg('points') ?? 0;
    }

    /**
     * Get total attempts.
     */
    public function totalAttempts()
    {
        return $this->attempts()->count();
    }
}