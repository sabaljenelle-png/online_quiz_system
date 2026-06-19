<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Attempt;

class DashboardController extends Controller
{

    public function index()
    {
        $user = auth()->user();

        if ($user->isTeacher()) {

            $quizCount = Quiz::where('teacher_id', $user->id)->count();

            $questionCount = Question::whereHas('quiz', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })->count();

            $attemptCount = Attempt::whereHas('quiz', function ($query) use ($user) {
                $query->where('teacher_id', $user->id);
            })->count();

            return view('dashboard', compact(
                'quizCount',
                'questionCount',
                'attemptCount'
            ));
        }

        return view('dashboard');
    }
}
