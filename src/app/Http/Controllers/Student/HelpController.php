<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\QuizAttempt;
use Illuminate\View\View;

class HelpController extends Controller
{
    public function index(): View{
        $availableModules=Module::published()->whereHas('course',fn($query)=>$query->published())->count();
        $availableQuizzes=Module::published()->whereHas('course',fn($query)=>$query->published())->whereHas('publishedQuestions')->count();
        $quizAttempts=QuizAttempt::where('user_id',auth()->id())->whereNotNull('completed_at')->count();
        return view('student.help.index',compact('availableModules','availableQuizzes','quizAttempts'));
    }
}