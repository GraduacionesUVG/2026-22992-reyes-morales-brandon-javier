<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View{
        $routePrefix=request()->routeIs('admin.*')?'admin':'gestor';

        $summary=[
            'courses'=>['label'=>'Cursos','total'=>Course::count(),'published'=>Course::where('status','published')->count(),'draft'=>Course::where('status','draft')->count()],
            'modules'=>['label'=>'Módulos','total'=>Module::count(),'published'=>Module::where('status','published')->count(),'draft'=>Module::where('status','draft')->count()],
            'lessons'=>['label'=>'Lecciones','total'=>Lesson::count(),'published'=>Lesson::where('status','published')->count(),'draft'=>Lesson::where('status','draft')->count()],
            'questions'=>['label'=>'Preguntas','total'=>Question::count(),'published'=>Question::where('status','published')->count(),'draft'=>Question::where('status','draft')->count()],
        ];

        $draftCourses=Course::where('status','draft')->latest()->limit(8)->get();
        $draftModules=Module::with('course')->where('status','draft')->latest()->limit(8)->get();
        $draftLessons=Lesson::with('module.course')->where('status','draft')->latest()->limit(8)->get();
        $draftQuestions=Question::with('module.course')->where('status','draft')->latest()->limit(10)->get();

        $publishedCourses=Course::where('status','published')->latest()->limit(5)->get();
        $publishedModules=Module::with('course')->where('status','published')->latest()->limit(5)->get();
        $publishedLessons=Lesson::with('module.course')->where('status','published')->latest()->limit(5)->get();
        $publishedQuestions=Question::with('module.course')->where('status','published')->latest()->limit(5)->get();

        return view('content.review.index',compact('routePrefix','summary','draftCourses','draftModules','draftLessons','draftQuestions','publishedCourses','publishedModules','publishedLessons','publishedQuestions'));
    }

    public function updateStatus(Request $request,string $type,int $id): RedirectResponse{
        $validated=$request->validate(['status'=>['required','in:draft,published']]);
        $modelClass=match($type){
            'courses'=>Course::class,
            'modules'=>Module::class,
            'lessons'=>Lesson::class,
            'questions'=>Question::class,
            default=>abort(404),
        };

        $item=$modelClass::findOrFail($id);
        $item->status=$validated['status'];
        $item->save();

        $message=$validated['status']==='published'?'Contenido publicado correctamente.':'Contenido enviado a borrador correctamente.';
        return back()->with('status',$message);
    }
}