<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QuestionController extends Controller
{
    public function all(): View{
        $courses=Course::with(['modules'=>fn($q)=>$q->withCount('questions')->orderBy('sort_order')->orderBy('id')])->orderBy('sort_order')->orderBy('id')->get();
        return view('content.questions.index',['courses'=>$courses,'routePrefix'=>$this->routePrefix()]);
    }

    public function index(Course $course,Module $module): View{
        $this->ensureModuleBelongsToCourse($course,$module);
        $module->load(['questions'=>fn($q)=>$q->withCount(['options','correctOptions'])->orderBy('sort_order')->orderBy('id')]);
        return view('content.questions.module',['course'=>$course,'module'=>$module,'routePrefix'=>$this->routePrefix()]);
    }

    public function create(Course $course,Module $module): View{
        $this->ensureModuleBelongsToCourse($course,$module);
        $suggestedSortOrder=((int)$module->questions()->max('sort_order'))+1;
        return view('content.questions.create',['course'=>$course,'module'=>$module,'routePrefix'=>$this->routePrefix(),'suggestedSortOrder'=>max(1,$suggestedSortOrder),'optionValues'=>['','','',''],'correctOptionIndex'=>0]);
    }

    public function store(Request $request,Course $course,Module $module): RedirectResponse{
        $this->ensureModuleBelongsToCourse($course,$module);
        $validated=$this->validatedData($request);

        DB::transaction(function() use($validated,$module){
            $question=Question::create([
                'module_id'=>$module->id,
                'question_text'=>$validated['question_text'],
                'explanation'=>$validated['explanation']??null,
                'points'=>$validated['points'],
                'sort_order'=>$validated['sort_order'],
                'status'=>$validated['status'],
            ]);

            foreach($validated['options'] as $index=>$optionText){
                QuestionOption::create([
                    'question_id'=>$question->id,
                    'option_text'=>$optionText,
                    'is_correct'=>(int)$validated['correct_option']===$index,
                    'sort_order'=>$index+1,
                ]);
            }
        });

        return redirect()->route($this->routePrefix().'.courses.modules.questions.index',[$course,$module])->with('status','La pregunta fue creada correctamente.');
    }

    public function edit(Course $course,Module $module,Question $question): View{
        $this->ensureQuestionHierarchy($course,$module,$question);
        $question->load('options');

        $options=$question->options->values();
        $optionValues=[];
        $correctOptionIndex=0;

        for($i=0;$i<4;$i++){
            $optionValues[$i]=$options[$i]->option_text??'';
            if(($options[$i]->is_correct??false)===true)$correctOptionIndex=$i;
        }

        return view('content.questions.edit',['course'=>$course,'module'=>$module,'question'=>$question,'routePrefix'=>$this->routePrefix(),'suggestedSortOrder'=>$question->sort_order,'optionValues'=>$optionValues,'correctOptionIndex'=>$correctOptionIndex]);
    }

    public function update(Request $request,Course $course,Module $module,Question $question): RedirectResponse{
        $this->ensureQuestionHierarchy($course,$module,$question);
        $validated=$this->validatedData($request);

        DB::transaction(function() use($validated,$question){
            $question->update([
                'question_text'=>$validated['question_text'],
                'explanation'=>$validated['explanation']??null,
                'points'=>$validated['points'],
                'sort_order'=>$validated['sort_order'],
                'status'=>$validated['status'],
            ]);

            $question->options()->delete();

            foreach($validated['options'] as $index=>$optionText){
                QuestionOption::create([
                    'question_id'=>$question->id,
                    'option_text'=>$optionText,
                    'is_correct'=>(int)$validated['correct_option']===$index,
                    'sort_order'=>$index+1,
                ]);
            }
        });

        return redirect()->route($this->routePrefix().'.courses.modules.questions.edit',[$course,$module,$question])->with('status','La pregunta fue actualizada correctamente.');
    }

    public function destroy(Course $course,Module $module,Question $question): RedirectResponse{
        $this->ensureQuestionHierarchy($course,$module,$question);
        $question->delete();

        return redirect()->route($this->routePrefix().'.courses.modules.questions.index',[$course,$module])->with('status','La pregunta fue eliminada.');
    }

    private function validatedData(Request $request): array{
        return $request->validate([
            'question_text'=>['required','string','max:2000'],
            'explanation'=>['nullable','string','max:3000'],
            'points'=>['required','integer','min:1','max:10'],
            'sort_order'=>['required','integer','min:0','max:9999'],
            'status'=>['required',Rule::in(['draft','published'])],
            'options'=>['required','array','size:4'],
            'options.*'=>['required','string','max:1000'],
            'correct_option'=>['required','integer','min:0','max:3'],
        ],[
            'question_text.required'=>'Debes escribir la pregunta.',
            'options.size'=>'Cada pregunta debe tener exactamente 4 opciones.',
            'options.*.required'=>'Todas las opciones de respuesta son obligatorias.',
            'correct_option.required'=>'Debes seleccionar cuál opción es la correcta.',
        ]);
    }

    private function ensureModuleBelongsToCourse(Course $course,Module $module): void{
        abort_unless($module->course_id===$course->id,404);
    }

    private function ensureQuestionHierarchy(Course $course,Module $module,Question $question): void{
        $this->ensureModuleBelongsToCourse($course,$module);
        abort_unless($question->module_id===$module->id,404);
    }

    private function routePrefix(): string{
        return auth()->user()->isAdmin()?'admin':'gestor';
    }
}