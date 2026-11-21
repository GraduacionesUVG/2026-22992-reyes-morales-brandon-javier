<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function all(): View
    {
        $courses=Course::with(['modules'=>fn($query)=>$query->withCount('lessons')->orderBy('sort_order')])->orderBy('sort_order')->orderBy('id')->get();

        return view('content.modules.index',[
            'courses'=>$courses,
            'routePrefix'=>$this->routePrefix(),
        ]);
    }

    public function index(Course $course): View
    {
        $course->load(['modules'=>fn($query)=>$query->withCount('lessons')->orderBy('sort_order')]);

        return view('content.modules.course',[
            'course'=>$course,
            'routePrefix'=>$this->routePrefix(),
        ]);
    }

    public function create(Course $course): View
    {
        return view('content.modules.create',[
            'course'=>$course,
            'routePrefix'=>$this->routePrefix(),
        ]);
    }

    public function store(Request $request,Course $course): RedirectResponse
    {
        $validated=$request->validate([
            'title'=>['required','string','max:255'],
            'description'=>['nullable','string','max:3000'],
            'status'=>['required',Rule::in(['draft','published'])],
            'sort_order'=>['required','integer','min:0','max:9999'],
        ]);

        Module::create([
            'course_id'=>$course->id,
            'title'=>$validated['title'],
            'slug'=>$this->uniqueSlug($course,$validated['title']),
            'description'=>$validated['description'] ?? null,
            'status'=>$validated['status'],
            'sort_order'=>$validated['sort_order'],
        ]);

        return redirect()->route($this->routePrefix().'.courses.modules.index',$course)
            ->with('status','El módulo fue creado correctamente.');
    }

    public function edit(Course $course,Module $module): View
    {
        $this->ensureBelongsToCourse($course,$module);

        return view('content.modules.edit',[
            'course'=>$course,
            'module'=>$module,
            'routePrefix'=>$this->routePrefix(),
        ]);
    }

    public function update(Request $request,Course $course,Module $module): RedirectResponse
    {
        $this->ensureBelongsToCourse($course,$module);

        $validated=$request->validate([
            'title'=>['required','string','max:255'],
            'description'=>['nullable','string','max:3000'],
            'status'=>['required',Rule::in(['draft','published'])],
            'sort_order'=>['required','integer','min:0','max:9999'],
        ]);

        $module->update([
            'title'=>$validated['title'],
            'slug'=>$module->title===$validated['title']?$module->slug:$this->uniqueSlug($course,$validated['title'],$module->id),
            'description'=>$validated['description'] ?? null,
            'status'=>$validated['status'],
            'sort_order'=>$validated['sort_order'],
        ]);

        return redirect()->route($this->routePrefix().'.courses.modules.index',$course)
            ->with('status','Los cambios del módulo fueron guardados.');
    }

    public function destroy(Course $course,Module $module): RedirectResponse
    {
        $this->ensureBelongsToCourse($course,$module);
        $module->delete();

        return redirect()->route($this->routePrefix().'.courses.modules.index',$course)
            ->with('status','El módulo fue eliminado.');
    }

    private function ensureBelongsToCourse(Course $course,Module $module): void
    {
        abort_unless($module->course_id===$course->id,404);
    }

    private function routePrefix(): string
    {
        return auth()->user()->isAdmin()?'admin':'gestor';
    }

    private function uniqueSlug(Course $course,string $title,?int $ignoreId=null): string
    {
        $base=Str::slug($title) ?: 'modulo';
        $slug=$base;
        $number=2;

        while(Module::where('course_id',$course->id)->where('slug',$slug)->when($ignoreId,fn($query)=>$query->where('id','!=',$ignoreId))->exists()){
            $slug=$base.'-'.$number;
            $number++;
        }
        return $slug;
    }
}