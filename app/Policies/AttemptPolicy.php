<?php

namespace App\Policies;

use App\Models\Attempt;
use App\Models\User;

class AttemptPolicy
{
    /**
     * Determine if the user can view the attempt.
     */
    public function view(User $user, Attempt $attempt): bool
    {
        return $user->id === $attempt->student_id || $user->id === $attempt->quiz->teacher_id;
    }

    /**
     * Determine if the user can submit the attempt.
     */
    public function submit(User $user, Attempt $attempt): bool
    {
        return $user->id === $attempt->student_id;
    }
}