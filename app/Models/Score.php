<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'student_answer',
        'user_answer',
        'is_correct',
        'points',
        'points_earned',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points' => 'integer',
        'points_earned' => 'integer',
    ];

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function student()
    {
        return $this->hasOneThrough(User::class, Attempt::class, 'id', 'id', 'attempt_id', 'student_id');
    }
}
