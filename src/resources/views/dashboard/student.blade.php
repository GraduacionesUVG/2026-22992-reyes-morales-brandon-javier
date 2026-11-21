@extends('layouts.app')
@section('title','Inicio · '.config('branding.site_name'))
@section('page_title','Inicio')
@section('page_description','Resumen de tu actividad en la plataforma')

@section('content')
@php $user=auth()->user(); @endphp

<div class="student-home">
    <div class="student-home-heading">
        <p class="student-home-eyebrow">Panel principal</p>
        <h1>Bienvenido, {{ $user->name }}</h1>
        <p>Continúa con tu proceso de aprendizaje y practica con el simulador.</p>
    </div>

    <div class="student-home-stats">
        <a href="{{ route('student.modules.index') }}" class="student-home-stat student-home-stat-link"><span>Cursos disponibles</span><strong>{{ $availableCourses }}</strong></a>
        <a href="{{ route('student.progress.index') }}" class="student-home-stat student-home-stat-link"><span>Progreso general</span><strong>{{ $progress }}%</strong></a>
        <a href="{{ route('student.quiz.index') }}" class="student-home-stat student-home-stat-link"><span>Simuladores</span><strong>{{ $evaluations }}</strong></a>
    </div>

    <div class="student-home-content" style="margin-top:24px;">
        <div>
            <span class="student-content-label">Guía rápida</span>
            <strong>Aprende cómo usar la plataforma</strong>
            <small>Consulta cómo estudiar módulos, guardar favoritos, buscar contenido y usar el simulador.</small>
        </div>
        <a href="{{ route('student.help.index') }}">Ver guía <svg viewBox="0 0 20 20" fill="none"><path d="M4 10H16M11 5L16 10L11 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    </div>

    <div class="student-home-content" style="margin-top:24px;">
        <div><span class="student-content-label">Contenido disponible</span><strong>{{ $publishedModules }} módulos</strong><small>{{ $completedLessons }} de {{ $publishedLessons }} lecciones completadas</small></div>
        <a href="{{ route('student.modules.index') }}">Ver módulos <svg viewBox="0 0 20 20" fill="none"><path d="M4 10H16M11 5L16 10L11 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    </div>

    <div class="student-home-content" style="margin-top:18px;">
        <div>
            <span class="student-content-label">Simulador</span>
            <strong>{{ $availableQuizzes }} módulo{{ $availableQuizzes===1?'':'s' }} con preguntas</strong>
            <small>
                @if($lastQuizAttempt)
                    Último resultado: {{ number_format((float)$lastQuizAttempt->score,2) }}% en {{ $lastQuizAttempt->module->title }}
                @else
                    Todavía no has realizado simuladores.
                @endif
            </small>
        </div>
        <a href="{{ route('student.quiz.index') }}">Ir al simulador <svg viewBox="0 0 20 20" fill="none"><path d="M4 10H16M11 5L16 10L11 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    </div>

    <section class="cms-list-card" style="margin-top:24px;">
        <div class="cms-form-card-header"><div><h2>Módulos recomendados</h2><p>Continúa revisando contenido publicado.</p></div></div>
        @forelse($nextModules as $module)
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ str_pad($module->sort_order,2,'0',STR_PAD_LEFT) }}</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row"><h2>{{ $module->title }}</h2><span class="cms-status is-published"><span></span>{{ $module->published_lessons_count }} lecciones</span></div>
                        <p>{{ $module->course->title }}</p>
                        <div class="course-admin-meta"><span>{{ $module->published_questions_count }} preguntas disponibles</span></div>
                    </div>
                </div>
                <div class="course-admin-actions"><a href="{{ route('student.modules.show',$module) }}" class="cms-secondary-button">Abrir</a></div>
            </article>
        @empty
            <div class="cms-empty"><h2>No hay módulos publicados</h2><p>Cuando exista contenido publicado, aparecerá aquí.</p></div>
        @endforelse
    </section>

    <section class="cms-list-card" style="margin-top:24px;">
        <div class="cms-form-card-header"><div><h2>Intentos recientes</h2><p>Resultados más recientes del simulador.</p></div></div>
        @forelse($recentAttempts as $attempt)
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ number_format((float)$attempt->score,0) }}%</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row"><h2>{{ $attempt->module->title }}</h2><span class="cms-status {{ $attempt->score>=70?'is-published':'is-draft' }}"><span></span>{{ $attempt->resultLabel() }}</span></div>
                        <p>{{ $attempt->module->course->title }}</p>
                        <div class="course-admin-meta"><span>{{ $attempt->correct_answers }} de {{ $attempt->total_questions }} correctas</span><span>•</span><span>{{ $attempt->completed_at?->format('d/m/Y H:i') }}</span></div>
                    </div>
                </div>
                <div class="course-admin-actions"><a href="{{ route('student.quiz.result',$attempt) }}" class="cms-secondary-button">Ver</a></div>
            </article>
        @empty
            <div class="cms-empty"><h2>Sin intentos todavía</h2><p>Realiza un simulador para ver tus resultados.</p></div>
        @endforelse
    </section>
</div>
@endsection