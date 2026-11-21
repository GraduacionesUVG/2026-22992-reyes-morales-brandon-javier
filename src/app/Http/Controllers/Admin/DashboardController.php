<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View{
        $usersCount=User::count();
        $studentsCount=User::where('role',User::ROLE_ESTUDIANTE)->count();
        $gestoresCount=User::where('role',User::ROLE_GESTOR)->count();
        $adminsCount=User::where('role',User::ROLE_ADMIN)->count();
        $coursesCount=Course::count();
        $publishedCoursesCount=Course::published()->count();
        $draftCoursesCount=Course::where('status','draft')->count();
        $modulesCount=Module::count();
        $publishedModulesCount=Module::published()->count();
        $lessonsCount=Lesson::count();
        $publishedLessonsCount=Lesson::published()->count();
        $questionsCount=Question::count();
        $publishedQuestionsCount=Question::published()->count();
        $quizAttemptsCount=QuizAttempt::whereNotNull('completed_at')->count();
        $averageScore=QuizAttempt::whereNotNull('completed_at')->avg('score');
        $publishedContent=$publishedCoursesCount+$publishedModulesCount+$publishedLessonsCount+$publishedQuestionsCount;
        $draftContent=$draftCoursesCount+Module::where('status','draft')->count()+Lesson::where('status','draft')->count()+Question::where('status','draft')->count();
        $latestAttempts=QuizAttempt::with(['user','module.course'])->whereNotNull('completed_at')->latest('completed_at')->limit(5)->get();
        $latestCourses=Course::latest()->limit(5)->get();

        return view('dashboard.admin',compact('usersCount','studentsCount','gestoresCount','adminsCount','coursesCount','publishedCoursesCount','draftCoursesCount','modulesCount','publishedModulesCount','lessonsCount','publishedLessonsCount','questionsCount','publishedQuestionsCount','quizAttemptsCount','averageScore','publishedContent','draftContent','latestAttempts','latestCourses'));
    }
}