@extends('layouts.app')
@section('title','Dashboard gestor · '.config('branding.site_name'))
@section('page_title','Dashboard gestor')
@section('page_description','Resumen de gestión de contenido')

@section('content')
<div class="student-home">
    <div class="student-home-heading">
        <p class="student-home-eyebrow">Panel de gestión</p>
        <h1>Bienvenido, {{ auth()->user()->name }}</h1>
        <p>Administra cursos, módulos, lecciones y preguntas del simulador.</p>
    </div>

    <div class="student-home-stats">
        <a href="{{ route('gestor.courses.index') }}" class="student-home-stat student-home-stat-link"><span>Cursos</span><strong>{{ $coursesCount }}</strong></a>
        <a href="{{ route('gestor.modules.index') }}" class="student-home-stat student-home-stat-link"><span>Módulos</span><strong>{{ $modulesCount }}</strong></a>
        <a href="{{ route('gestor.lessons.index') }}" class="student-home-stat student-home-stat-link"><span>Lecciones</span><strong>{{ $lessonsCount }}</strong></a>
        <a href="{{ route('gestor.questions.index') }}" class="student-home-stat student-home-stat-link"><span>Preguntas</span><strong>{{ $questionsCount }}</strong></a>
    </div>

    <div class="student-home-content" style="margin-top:24px;">
        <div><span class="student-content-label">Cursos</span><strong>{{ $publishedCoursesCount }} publicados</strong><small>{{ $draftCoursesCount }} en borrador</small></div>
        <a href="{{ route('gestor.courses.index') }}">Gestionar cursos <svg viewBox="0 0 20 20" fill="none"><path d="M4 10H16M11 5L16 10L11 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    </div>

    <div class="student-home-content" style="margin-top:18px;">
        <div><span class="student-content-label">Banco de preguntas</span><strong>{{ $publishedQuestionsCount }} publicadas</strong><small>{{ $draftQuestionsCount }} en borrador · {{ $quizAttemptsCount }} intentos realizados</small></div>
        <a href="{{ route('gestor.questions.index') }}">Gestionar preguntas <svg viewBox="0 0 20 20" fill="none"><path d="M4 10H16M11 5L16 10L11 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></a>
    </div>

    <section class="cms-list-card" style="margin-top:24px;">
        <div class="cms-form-card-header"><div><h2>Preguntas recientes</h2><p>Últimas preguntas agregadas al banco.</p></div></div>
        @forelse($latestQuestions as $question)
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ str_pad($question->sort_order,2,'0',STR_PAD_LEFT) }}</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row"><h2>{{ $question->question_text }}</h2><span class="cms-status {{ $question->status==='published'?'is-published':'is-draft' }}"><span></span>{{ $question->status==='published'?'Publicado':'Borrador' }}</span></div>
                        <p>{{ $question->module?->title ?? 'Sin módulo' }} · {{ $question->module?->course?->title ?? 'Sin curso' }}</p>
                    </div>
                </div>
            </article>
        @empty
            <div class="cms-empty"><h2>No hay preguntas recientes</h2><p>Crea preguntas desde el banco de preguntas.</p></div>
        @endforelse
    </section>
</div>
@endsection