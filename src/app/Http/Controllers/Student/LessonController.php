<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function show(Lesson $lesson): View{
        $lesson->load(['module.course','resources']);
        $this->ensureAvailable($lesson);

        $previousLesson=Lesson::published()
            ->where('module_id',$lesson->module_id)
            ->where(function($query)use($lesson){
                $query->where('sort_order','<',$lesson->sort_order)
                    ->orWhere(fn($query)=>$query
                        ->where('sort_order',$lesson->sort_order)
                        ->where('id','<',$lesson->id));
            })
            ->orderByDesc('sort_order')
            ->orderByDesc('id')
            ->first();

        $nextLesson=Lesson::published()
            ->where('module_id',$lesson->module_id)
            ->where(function($query)use($lesson){
                $query->where('sort_order','>',$lesson->sort_order)
                    ->orWhere(fn($query)=>$query
                        ->where('sort_order',$lesson->sort_order)
                        ->where('id','>',$lesson->id));
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->first();

        $publishedLessons=$lesson->module
            ->publishedLessons()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id']);

        $position=$publishedLessons->search(
            fn($item)=>$item->id===$lesson->id
        );

        $position=$position===false?null:$position+1;

        $isCompleted=auth()->user()
            ->lessonProgresses()
            ->where('lesson_id',$lesson->id)
            ->exists();

        $isFavorite=auth()->user()
            ->lessonFavorites()
            ->where('lesson_id',$lesson->id)
            ->exists();

        return view('student.lessons.show',[
            'lesson'=>$lesson,
            'previousLesson'=>$previousLesson,
            'nextLesson'=>$nextLesson,
            'position'=>$position,
            'totalLessons'=>$publishedLessons->count(),
            'isCompleted'=>$isCompleted,
            'isFavorite'=>$isFavorite
        ]);
    }

    public function complete(Lesson $lesson): RedirectResponse{
        $lesson->load('module.course');
        $this->ensureAvailable($lesson);

        auth()->user()->lessonProgresses()->updateOrCreate(
            ['lesson_id'=>$lesson->id],
            ['completed_at'=>now()]
        );

        return back()->with(
            'status',
            'Lección marcada como completada.'
        );
    }

    public function uncomplete(Lesson $lesson): RedirectResponse{
        $lesson->load('module.course');
        $this->ensureAvailable($lesson);

        auth()->user()
            ->lessonProgresses()
            ->where('lesson_id',$lesson->id)
            ->delete();

        return back()->with(
            'status',
            'La lección volvió a estado pendiente.'
        );
    }

    private function ensureAvailable(Lesson $lesson): void{
        abort_unless(
            $lesson->status==='published'
            && $lesson->module?->status==='published'
            && $lesson->module?->course?->status==='published',
            404
        );
    }
}