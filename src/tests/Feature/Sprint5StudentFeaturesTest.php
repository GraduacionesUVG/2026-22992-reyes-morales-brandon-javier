<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonFavorite;
use App\Models\Module;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Sprint5StudentFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private function user(string $role='estudiante'): User{
        return User::create([
            'name'=>'Usuario '.$role,
            'email'=>$role.'_'.uniqid().'@test.com',
            'password'=>'12345678',
            'role'=>$role,
        ]);
    }

    private function publishedStructure(): array{
        $admin=$this->user('admin');

        $course=Course::create([
            'title'=>'Curso de conducción',
            'slug'=>'curso-conduccion',
            'description'=>'Contenido teórico de conducción.',
            'status'=>'published',
            'sort_order'=>1,
            'created_by'=>$admin->id,
        ]);

        $module=Module::create([
            'course_id'=>$course->id,
            'title'=>'Licencia y obligaciones',
            'slug'=>'licencia-y-obligaciones',
            'description'=>'Normativa básica.',
            'sort_order'=>1,
            'status'=>'published',
        ]);

        $lesson=Lesson::create([
            'module_id'=>$module->id,
            'title'=>'Licencia de conducir',
            'slug'=>'licencia-de-conducir',
            'summary'=>'Documento obligatorio para conducir.',
            'content'=>'La licencia de conducir vigente permite manejar legalmente.',
            'estimated_minutes'=>5,
            'sort_order'=>1,
            'status'=>'published',
        ]);

        return compact('course','module','lesson');
    }

    private function addQuestion(Module $module,string $text='¿Qué documento necesitas para conducir?'): Question{
        $question=Question::create([
            'module_id'=>$module->id,
            'question_text'=>$text,
            'explanation'=>'La licencia vigente es obligatoria.',
            'points'=>1,
            'sort_order'=>1,
            'status'=>'published',
        ]);

        QuestionOption::create(['question_id'=>$question->id,'option_text'=>'Pasaporte','is_correct'=>false,'sort_order'=>1]);
        QuestionOption::create(['question_id'=>$question->id,'option_text'=>'Licencia vigente','is_correct'=>true,'sort_order'=>2]);
        QuestionOption::create(['question_id'=>$question->id,'option_text'=>'Carné estudiantil','is_correct'=>false,'sort_order'=>3]);
        QuestionOption::create(['question_id'=>$question->id,'option_text'=>'Recibo de luz','is_correct'=>false,'sort_order'=>4]);

        return $question;
    }

    public function test_estudiante_puede_ver_dashboard_modulos_busqueda_favoritos_y_simulador(): void{
        $student=$this->user();
        $data=$this->publishedStructure();
        $this->addQuestion($data['module']);

        $this->actingAs($student)->get(route('student.dashboard'))->assertOk();
        $this->actingAs($student)->get(route('student.modules.index'))->assertOk()->assertSee('Licencia y obligaciones');
        $this->actingAs($student)->get(route('student.search.index',['q'=>'licencia']))->assertOk()->assertSee('Licencia de conducir');
        $this->actingAs($student)->get(route('student.favorites.index'))->assertOk();
        $this->actingAs($student)->get(route('student.quiz.index'))->assertOk()->assertSee('Licencia y obligaciones');
    }

    public function test_buscador_muestra_contenido_publicado_y_oculta_borradores(): void{
        $student=$this->user();
        $data=$this->publishedStructure();

        $draftModule=Module::create([
            'course_id'=>$data['course']->id,
            'title'=>'Módulo borrador licencia',
            'slug'=>'modulo-borrador',
            'description'=>'No debe aparecer.',
            'sort_order'=>2,
            'status'=>'draft',
        ]);

        Lesson::create([
            'module_id'=>$draftModule->id,
            'title'=>'Lección borrador licencia',
            'slug'=>'leccion-borrador',
            'summary'=>'No visible para estudiante.',
            'content'=>'Contenido privado.',
            'estimated_minutes'=>5,
            'sort_order'=>1,
            'status'=>'draft',
        ]);

        $this->actingAs($student)
            ->get(route('student.search.index',['q'=>'licencia']))
            ->assertOk()
            ->assertSee('Licencia de conducir')
            ->assertDontSee('Lección borrador licencia');
    }

    public function test_estudiante_puede_agregar_y_quitar_favorito(): void{
        $student=$this->user();
        $data=$this->publishedStructure();

        $this->actingAs($student)
            ->post(route('student.favorites.toggle',$data['lesson']))
            ->assertRedirect();

        $this->assertDatabaseHas('lesson_favorites',[
            'user_id'=>$student->id,
            'lesson_id'=>$data['lesson']->id,
        ]);

        $this->actingAs($student)
            ->post(route('student.favorites.toggle',$data['lesson']))
            ->assertRedirect();

        $this->assertDatabaseMissing('lesson_favorites',[
            'user_id'=>$student->id,
            'lesson_id'=>$data['lesson']->id,
        ]);
    }

    public function test_estudiante_no_puede_marcar_como_favorito_una_leccion_no_publicada(): void{
        $student=$this->user();
        $data=$this->publishedStructure();

        $draftLesson=Lesson::create([
            'module_id'=>$data['module']->id,
            'title'=>'Lección privada',
            'slug'=>'leccion-privada',
            'summary'=>'No publicada.',
            'content'=>'Contenido no visible.',
            'estimated_minutes'=>5,
            'sort_order'=>2,
            'status'=>'draft',
        ]);

        $this->actingAs($student)
            ->post(route('student.favorites.toggle',$draftLesson))
            ->assertNotFound();

        $this->assertDatabaseCount('lesson_favorites',0);
    }

    public function test_simulador_crea_intento_resultado_y_respuestas(): void{
        $student=$this->user();
        $data=$this->publishedStructure();

        $question1=$this->addQuestion($data['module'],'¿Qué documento necesitas para conducir?');

        $question2=Question::create([
            'module_id'=>$data['module']->id,
            'question_text'=>'¿Qué acción mejora la seguridad vial?',
            'explanation'=>'Respetar señales reduce riesgos.',
            'points'=>1,
            'sort_order'=>2,
            'status'=>'published',
        ]);

        QuestionOption::create(['question_id'=>$question2->id,'option_text'=>'Ignorar señales','is_correct'=>false,'sort_order'=>1]);
        $correct2=QuestionOption::create(['question_id'=>$question2->id,'option_text'=>'Respetar señales','is_correct'=>true,'sort_order'=>2]);
        QuestionOption::create(['question_id'=>$question2->id,'option_text'=>'Usar teléfono','is_correct'=>false,'sort_order'=>3]);
        QuestionOption::create(['question_id'=>$question2->id,'option_text'=>'Acelerar en zona escolar','is_correct'=>false,'sort_order'=>4]);

        $wrong1=$question1->options()->where('is_correct',false)->first();

        $this->actingAs($student)
            ->post(route('student.quiz.submit',$data['module']),[
                'answers'=>[
                    $question1->id=>$wrong1->id,
                    $question2->id=>$correct2->id,
                ],
            ])
            ->assertRedirect();

        $attempt=QuizAttempt::first();

        $this->assertNotNull($attempt);
        $this->assertSame($student->id,$attempt->user_id);
        $this->assertSame($data['module']->id,$attempt->module_id);
        $this->assertSame(2,$attempt->total_questions);
        $this->assertSame(1,$attempt->correct_answers);
        $this->assertEquals(50.00,(float)$attempt->score);
        $this->assertDatabaseCount('quiz_attempt_answers',2);

        $this->actingAs($student)
            ->get(route('student.quiz.result',$attempt))
            ->assertOk()
            ->assertSee('50.00%')
            ->assertSee('Necesita repaso');
    }

    public function test_estudiante_no_puede_ver_resultado_de_otro_estudiante(): void{
        $student=$this->user();
        $other=$this->user();
        $data=$this->publishedStructure();

        $attempt=QuizAttempt::create([
            'user_id'=>$other->id,
            'module_id'=>$data['module']->id,
            'total_questions'=>1,
            'correct_answers'=>1,
            'score'=>100,
            'started_at'=>now(),
            'completed_at'=>now(),
        ]);

        $this->actingAs($student)
            ->get(route('student.quiz.result',$attempt))
            ->assertForbidden();
    }

    public function test_roles_no_autorizados_no_entran_a_rutas_de_estudiante(): void{
        $admin=$this->user('admin');

        $this->actingAs($admin)
            ->get(route('student.dashboard'))
            ->assertForbidden();
    }

    public function test_estudiante_no_entra_a_paneles_admin_ni_gestor(): void{
        $student=$this->user();

        $this->actingAs($student)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($student)->get(route('gestor.dashboard'))->assertForbidden();
    }
}
