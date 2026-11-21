<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(): View
    {
        $courses=Course::withCount(['modules','lessons'])->orderBy('sort_order')->orderBy('id')->get();
        return view('content.courses.index',['courses'=>$courses,'routePrefix'=>$this->routePrefix()]);
    }
    
    public function create(): View
    {
        return view('content.courses.create',['routePrefix'=>$this->routePrefix()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated=$request->validate([
            'title'=>['required','string','max:255'],
            'description'=>['nullable','string','max:3000'],
            'status'=>['required',Rule::in(['draft','published'])],
            'sort_order'=>['required','integer','min:0','max:9999'],
        ]);

        Course::create([
            'title'=>$validated['title'],
            'slug'=>$this->uniqueSlug($validated['title']),
            'description'=>$validated['description'] ?? null,
            'status'=>$validated['status'],
            'sort_order'=>$validated['sort_order'],
            'created_by'=>$request->user()->id,
        ]);

        return redirect()->route($this->routePrefix().'.courses.index')->with('status','El curso fue creado correctamente.');
    }

    public function edit(Course $course): View
    {
        return view('content.courses.edit',['course'=>$course,'routePrefix'=>$this->routePrefix()]);
    }

    public function update(Request $request,Course $course): RedirectResponse
    {
        $validated=$request->validate([
            'title'=>['required','string','max:255'],
            'description'=>['nullable','string','max:3000'],
            'status'=>['required',Rule::in(['draft','published'])],
            'sort_order'=>['required','integer','min:0','max:9999'],
        ]);

        $course->update([
            'title'=>$validated['title'],
            'slug'=>$course->title===$validated['title']?$course->slug:$this->uniqueSlug($validated['title'],$course->id),
            'description'=>$validated['description'] ?? null,
            'status'=>$validated['status'],
            'sort_order'=>$validated['sort_order'],
        ]);

        return redirect()->route($this->routePrefix().'.courses.index')->with('status','Los cambios del curso fueron guardados.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        $course->delete();
        return redirect()->route($this->routePrefix().'.courses.index')->with('status','El curso fue eliminado.');
    }

    private function routePrefix(): string
    {
        return auth()->user()->isAdmin()?'admin':'gestor';
    }

    private function uniqueSlug(string $title,?int $ignoreId=null): string
    {
        $base=Str::slug($title) ?: 'curso';
        $slug=$base;
        $number=2;

        while(Course::where('slug',$slug)->when($ignoreId,fn($query)=>$query->where('id','!=',$ignoreId))->exists()){
            $slug=$base.'-'.$number;
            $number++;
        }

        return $slug;
    }
}