<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonFavorite;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View{
        $favorites=LessonFavorite::with('lesson.module.course')
            ->where('user_id',auth()->id())
            ->whereHas('lesson',fn($q)=>$q->published()->whereHas('module',fn($q)=>$q->published()->whereHas('course',fn($q)=>$q->published())))
            ->latest()
            ->get();

        return view('student.favorites.index',['favorites'=>$favorites]);
    }

    public function toggle(Lesson $lesson): RedirectResponse{
        $lesson->load('module.course');
        $this->ensureAvailable($lesson);

        $favorite=LessonFavorite::where('user_id',auth()->id())->where('lesson_id',$lesson->id)->first();

        if($favorite){
            $favorite->delete();
            return back()->with('status','Lección eliminada de favoritos.');
        }

        LessonFavorite::create(['user_id'=>auth()->id(),'lesson_id'=>$lesson->id]);
        return back()->with('status','Lección agregada a favoritos.');
    }

    public function destroy(LessonFavorite $favorite): RedirectResponse{
        abort_unless($favorite->user_id===auth()->id(),403);
        $favorite->delete();
        return back()->with('status','Lección eliminada de favoritos.');
    }

    private function ensureAvailable(Lesson $lesson): void{
        abort_unless($lesson->status==='published'&&$lesson->module?->status==='published'&&$lesson->module?->course?->status==='published',404);
    }
}