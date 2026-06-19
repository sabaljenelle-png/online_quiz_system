<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;

class QuizPolicy
{
    /**
     * Determine if the user can create quizzes.
     */
    public function create(User $user): bool
    {
        return $user->isTeacher();
    }

    /**
     * Determine if the user can update the quiz.
     */
    public function update(User $user, Quiz $quiz): bool
    {
        return $user->id === $quiz->teacher_id;
    }

    /**
     * Determine if the user can delete the quiz.
     */
    public function delete(User $user, Quiz $quiz): bool
    {
        return $user->id === $quiz->teacher_id;
    }

    /**
     * Determine if the user can view the quiz.
     */
    public function view(User $user, Quiz $quiz): bool
    {
        if ($quiz->is_published) {
            return true;
        }

        return $user->id === $quiz->teacher_id;
    }
}