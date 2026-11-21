<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(): View{
        $modules=Module::with('course')->withCount('publishedQuestions')->where('status','published')->whereHas('course',fn($q)=>$q->where('status','published'))->whereHas('publishedQuestions')->orderBy('sort_order')->orderBy('id')->get();
        $latestAttempts=QuizAttempt::where('user_id',auth()->id())->whereIn('module_id',$modules->pluck('id'))->whereNotNull('completed_at')->latest('completed_at')->get()->unique('module_id')->keyBy('module_id');
        return view('student.quiz.index',['modules'=>$modules,'latestAttempts'=>$latestAttempts]);
    }

    public function show(Module $module): View|RedirectResponse{
        $this->ensureAvailableModule($module);
        $questions=$this->publishedQuestions($module);
        if($questions->isEmpty())return redirect()->route('student.quiz.index')->with('status','Este módulo todavía no tiene preguntas publicadas.');
        return view('student.quiz.show',['module'=>$module,'questions'=>$questions]);
    }

    public function submit(Request $request,Module $module): RedirectResponse{
        $this->ensureAvailableModule($module);
        $questions=$this->publishedQuestions($module);
        if($questions->isEmpty())return redirect()->route('student.quiz.index')->with('status','Este módulo todavía no tiene preguntas publicadas.');

        $rules=['answers'=>['required','array','size:'.$questions->count()]];
        foreach($questions as $question)$rules['answers.'.$question->id]=['required','integer'];

        $validated=$request->validate($rules,[
            'answers.required'=>'Debes responder todas las preguntas.',
            'answers.size'=>'Debes responder todas las preguntas.',
            'answers.*.required'=>'Todas las preguntas necesitan una respuesta.',
        ]);

        $attempt=DB::transaction(function() use($questions,$validated,$module){
            $attempt=QuizAttempt::create(['user_id'=>auth()->id(),'module_id'=>$module->id,'total_questions'=>$questions->count(),'correct_answers'=>0,'score'=>0,'started_at'=>now(),'completed_at'=>now()]);
            $correctAnswers=0;

            foreach($questions as $question){
                $selectedOptionId=(int)$validated['answers'][$question->id];
                $selectedOption=$question->options->firstWhere('id',$selectedOptionId);
                if(!$selectedOption)throw ValidationException::withMessages(['answers.'.$question->id=>'La opción seleccionada no pertenece a esta pregunta.']);
                $isCorrect=(bool)$selectedOption->is_correct;
                if($isCorrect)$correctAnswers++;

                QuizAttemptAnswer::create(['quiz_attempt_id'=>$attempt->id,'question_id'=>$question->id,'question_option_id'=>$selectedOption->id,'is_correct'=>$isCorrect]);
            }

            $attempt->update(['correct_answers'=>$correctAnswers,'score'=>$questions->count()>0?round(($correctAnswers/$questions->count())*100,2):0]);
            return $attempt;
        });

        return redirect()->route('student.quiz.result',$attempt);
    }

    public function result(QuizAttempt $attempt): View{
        abort_unless($attempt->user_id===auth()->id(),403);
        $attempt->load(['module.course','answers.question.options','answers.selectedOption']);
        return view('student.quiz.result',['attempt'=>$attempt]);
    }

    public function history(): View{
        $attempts=QuizAttempt::with('module.course')->where('user_id',auth()->id())->whereNotNull('completed_at')->latest('completed_at')->get();
        return view('student.quiz.history',['attempts'=>$attempts]);
    }

    private function ensureAvailableModule(Module $module): void{
        $module->loadMissing('course');
        abort_unless($module->status==='published'&&$module->course?->status==='published',404);
    }

    private function publishedQuestions(Module $module){
        return Question::with('options')->published()->where('module_id',$module->id)->ordered()->get();
    }
}