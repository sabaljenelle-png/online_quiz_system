<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'question_text',
        'type',
        'question_type',
        'answer_mode',
        'points',
        'order',
    ];

    protected $casts = [
        'points' => 'integer',
        'order' => 'integer',
        'answer_mode' => 'string',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class)->orderBy('order');
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }

    public function attempts()
    {
        return $this->belongsToMany(Attempt::class, 'attempt_question')->withPivot('option_id');
    }

    public function setQuestionTypeAttribute($value): void
    {
        $this->attributes['question_type'] = $value;
        $this->attributes['type'] = $value;
    }

    public function setTypeAttribute($value): void
    {
        $this->attributes['type'] = $value;
        $this->attributes['question_type'] = $value;
    }


    public function allowsMultipleAnswers(): bool
    {
        return ($this->question_type ?? $this->type) === 'multiple_choice' && ($this->answer_mode ?? 'radio') === 'checkbox';
    }

    public function isSingleAnswer(): bool
    {
        return ! $this->allowsMultipleAnswers();
    }

    public function correctOptions()
    {
        return $this->options()->where('is_correct', true)->get();
    }

    public function isAnswerCorrect($answer)
    {
        return in_array($answer, $this->correctOptions()->pluck('id')->toArray());
    }
}
