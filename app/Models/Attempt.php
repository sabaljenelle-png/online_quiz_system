<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'quiz_id',
        'score',
        'is_passed',
        'status',
        'started_at',
        'completed_at',
        'total_score',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_score' => 'integer',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the student who made this attempt.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the quiz attempted.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get all scores for this attempt.
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    // ===== HELPER METHODS =====

    /**
     * Calculate and save total score.
     */
    public function calculateTotalScore()
    {
        $totalScore = $this->scores()
            ->where('is_correct', true)
            ->sum('points_earned');
        
        $this->update(['total_score' => $totalScore]);
        return $totalScore;
    }

    /**
     * Get percentage score.
     */
    public function percentageScore()
    {
        if (! is_null($this->score)) {
            return $this->score;
        }

        $totalQuestions = $this->quiz?->questions()->count() ?? 0;
        return $totalQuestions > 0 ? round(($this->total_score / $totalQuestions) * 100) : 0;
    }

    /**
     * Check if passed.
     */
    public function isPassed()
    {
        $passingScore = $this->quiz->passing_score ?? 50;
        return $this->percentageScore() >= $passingScore;
    }

    /**
     * Get duration in minutes.
     */
    public function getDuration()
    {
        if ($this->completed_at && $this->started_at) {
            return $this->completed_at->diffInMinutes($this->started_at);
        }
        return null;
    }

    public function questions()
{
    return $this->belongsToMany(
        Question::class,
        'attempt_question'
    )->withPivot('option_id');
}
}