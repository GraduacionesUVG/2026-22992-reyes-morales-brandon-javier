@extends('layouts.app')
@section('title','Dashboard administrador · '.config('branding.site_name'))
@section('page_title','Dashboard administrador')
@section('page_description','Resumen general de la plataforma')

@section('content')
<div class="student-home">
    <div class="student-home-heading">
        <p class="student-home-eyebrow">Panel administrativo</p>
        <h1>Bienvenido, {{ auth()->user()->name }}</h1>
        <p>Resumen de usuarios, contenido, preguntas y simuladores.</p>
    </div>

    <div class="student-home-stats">
        <div class="student-home-stat"><span>Usuarios</span><strong>{{ $usersCount }}</strong></div>
        <a href="{{ route('admin.courses.index') }}" class="student-home-stat student-home-stat-link"><span>Cursos</span><strong>{{ $coursesCount }}</strong></a>
        <a href="{{ route('admin.lessons.index') }}" class="student-home-stat student-home-stat-link"><span>Lecciones</span><strong>{{ $lessonsCount }}</strong></a>
        <a href="{{ route('admin.questions.index') }}" class="student-home-stat student-home-stat-link"><span>Preguntas</span><strong>{{ $questionsCount }}</strong></a>
    </div>

    <div class="student-home-stats" style="margin-top:18px;">
        <div class="student-home-stat"><span>Estudiantes</span><strong>{{ $studentsCount }}</strong></div>
        <div class="student-home-stat"><span>Gestores</span><strong>{{ $gestoresCount }}</strong></div>
        <div class="student-home-stat"><span>Intentos simulador</span><strong>{{ $quizAttemptsCount }}</strong></div>
        <div class="student-home-stat"><span>Promedio general</span><strong>{{ number_format((float)($averageScore ?? 0),2) }}%</strong></div>
    </div>

    <div class="student-home-content" style="margin-top:24px;">
        <div><span class="student-content-label">Estado del contenido</span><strong>{{ $publishedContent }} elementos publicados</strong><small>{{ $draftContent }} elementos en borrador</small></div>
        <a href="{{ route('admin.questions.index') }}">Revisar banco de preguntas <svg viewBox="0 0 20 20" fill="none"><path d="M4 10H16M11 5L16 10L11 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    </div>

    <section class="cms-list-card" style="margin-top:24px;">
        <div class="cms-form-card-header"><div><h2>Últimos intentos del simulador</h2><p>Resultados recientes de estudiantes.</p></div></div>
        @forelse($latestAttempts as $attempt)
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ number_format((float)$attempt->score,0) }}%</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row"><h2>{{ $attempt->user?->name ?? 'Usuario eliminado' }}</h2><span class="cms-status {{ $attempt->score>=70?'is-published':'is-draft' }}"><span></span>{{ $attempt->resultLabel() }}</span></div>
                        <p>{{ $attempt->module?->title ?? 'Módulo eliminado' }}</p>
                        <div class="course-admin-meta"><span>{{ $attempt->correct_answers }} de {{ $attempt->total_questions }} correctas</span><span>•</span><span>{{ $attempt->completed_at?->format('d/m/Y H:i') }}</span></div>
                    </div>
                </div>
            </article>
        @empty
            <div class="cms-empty"><h2>Sin intentos todavía</h2><p>Cuando los estudiantes usen el simulador, aparecerán aquí.</p></div>
        @endforelse
    </section>
</div>
@endsection