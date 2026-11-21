<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\View\View;

class ProgressController extends Controller
{
    public function index(): View{
        $courses=Course::published()
            ->with('publishedModules.publishedLessons')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $completedLessonIds=auth()->user()
            ->lessonProgresses()
            ->pluck('lesson_id');

        $totalLessons=0;
        $completedLessons=0;

        foreach($courses as $course){
            $courseTotal=0;
            $courseCompleted=0;

            foreach($course->publishedModules as $module){
                $moduleTotal=$module->publishedLessons->count();

                $moduleCompleted=$module->publishedLessons
                    ->whereIn('id',$completedLessonIds)
                    ->count();

                $module->setAttribute(
                    'completed_lessons_count',
                    $moduleCompleted
                );

                $module->setAttribute(
                    'progress_percent',
                    $moduleTotal>0
                        ?(int)round(($moduleCompleted/$moduleTotal)*100)
                        :0
                );

                $courseTotal+=$moduleTotal;
                $courseCompleted+=$moduleCompleted;
            }

            $course->setAttribute(
                'total_lessons_count',
                $courseTotal
            );

            $course->setAttribute(
                'completed_lessons_count',
                $courseCompleted
            );

            $course->setAttribute(
                'progress_percent',
                $courseTotal>0
                    ?(int)round(($courseCompleted/$courseTotal)*100)
                    :0
            );

            $totalLessons+=$courseTotal;
            $completedLessons+=$courseCompleted;
        }

        $progress=$totalLessons>0
            ?(int)round(($completedLessons/$totalLessons)*100)
            :0;

        return view(
            'student.progress.index',
            compact(
                'courses',
                'totalLessons',
                'completedLessons',
                'progress'
            )
        );
    }
}