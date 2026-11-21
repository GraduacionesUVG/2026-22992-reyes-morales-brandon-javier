<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function index(): View{
        $courses=Course::published()
            ->with([
                'publishedModules'=>fn($query)=>$query
                    ->with('publishedLessons')
                    ->withCount('publishedLessons')
                    ->orderBy('sort_order')
                    ->orderBy('id')
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $completedLessonIds=auth()->user()
            ->lessonProgresses()
            ->pluck('lesson_id');

        foreach($courses as $course){
            foreach($course->publishedModules as $module){
                $completed=$module->publishedLessons
                    ->whereIn('id',$completedLessonIds)
                    ->count();

                $total=$module->publishedLessons->count();

                $module->setAttribute(
                    'completed_lessons_count',
                    $completed
                );

                $module->setAttribute(
                    'progress_percent',
                    $total>0
                        ?(int)round(($completed/$total)*100)
                        :0
                );
            }
        }

        return view(
            'student.modules.index',
            compact('courses')
        );
    }

    public function show(Module $module): View{
        $module->load('course');

        abort_unless(
            $module->status==='published'
            && $module->course?->status==='published',
            404
        );

        $module->load([
            'publishedLessons'=>fn($query)=>$query
                ->orderBy('sort_order')
                ->orderBy('id')
        ]);

        $completedLessonIds=auth()->user()
            ->lessonProgresses()
            ->whereIn(
                'lesson_id',
                $module->publishedLessons->pluck('id')
            )
            ->pluck('lesson_id');

        $totalLessons=$module->publishedLessons->count();
        $completedLessons=$completedLessonIds->count();

        $progress=$totalLessons>0
            ?(int)round(($completedLessons/$totalLessons)*100)
            :0;

        return view(
            'student.modules.show',
            compact(
                'module',
                'completedLessonIds',
                'completedLessons',
                'totalLessons',
                'progress'
            )
        );
    }
}