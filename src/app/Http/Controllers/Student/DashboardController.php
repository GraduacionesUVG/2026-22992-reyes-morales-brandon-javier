<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\QuizAttempt;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View{
        $availableCourses=Course::published()->count();
        $publishedModules=Module::published()->whereHas('course',fn($query)=>$query->published())->count();
        $visibleLessons=Lesson::published()->whereHas('module',fn($query)=>$query->published()->whereHas('course',fn($query)=>$query->published()));
        $publishedLessons=(clone $visibleLessons)->count();
        $completedLessons=auth()->user()->lessonProgresses()->whereHas('lesson',function($query){
            $query->published()->whereHas('module',fn($query)=>$query->published()->whereHas('course',fn($query)=>$query->published()));
        })->count();

        $progress=$publishedLessons>0?(int)round(($completedLessons/$publishedLessons)*100):0;
        $availableQuizzes=Module::published()->whereHas('course',fn($query)=>$query->published())->whereHas('publishedQuestions')->count();
        $evaluations=QuizAttempt::where('user_id',auth()->id())->whereNotNull('completed_at')->count();
        $lastQuizAttempt=QuizAttempt::with('module.course')->where('user_id',auth()->id())->whereNotNull('completed_at')->latest('completed_at')->first();
        $recentAttempts=QuizAttempt::with('module.course')->where('user_id',auth()->id())->whereNotNull('completed_at')->latest('completed_at')->limit(3)->get();
        $nextModules=Module::with('course')->withCount(['publishedLessons','publishedQuestions'])->published()->whereHas('course',fn($query)=>$query->published())->orderBy('sort_order')->orderBy('id')->limit(3)->get();

        return view('dashboard.student',compact('availableCourses','publishedModules','publishedLessons','completedLessons','progress','availableQuizzes','evaluations','lastQuizAttempt','recentAttempts','nextModules'));
    }
}