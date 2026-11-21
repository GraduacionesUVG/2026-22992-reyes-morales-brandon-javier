<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View{
        $coursesCount=Course::count();
        $publishedCoursesCount=Course::published()->count();
        $draftCoursesCount=Course::where('status','draft')->count();
        $modulesCount=Module::count();
        $publishedModulesCount=Module::published()->count();
        $draftModulesCount=Module::where('status','draft')->count();
        $lessonsCount=Lesson::count();
        $publishedLessonsCount=Lesson::published()->count();
        $draftLessonsCount=Lesson::where('status','draft')->count();
        $questionsCount=Question::count();
        $publishedQuestionsCount=Question::published()->count();
        $draftQuestionsCount=Question::where('status','draft')->count();
        $quizAttemptsCount=QuizAttempt::whereNotNull('completed_at')->count();
        $averageScore=QuizAttempt::whereNotNull('completed_at')->avg('score');
        $latestCourses=Course::latest()->limit(5)->get();
        $latestQuestions=Question::with('module.course')->latest()->limit(5)->get();

        return view('dashboard.manager',compact('coursesCount','publishedCoursesCount','draftCoursesCount','modulesCount','publishedModulesCount','draftModulesCount','lessonsCount','publishedLessonsCount','draftLessonsCount','questionsCount','publishedQuestionsCount','draftQuestionsCount','quizAttemptsCount','averageScore','latestCourses','latestQuestions'));
    }
}