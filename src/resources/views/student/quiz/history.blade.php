@extends('layouts.app')
@section('title','Historial de simuladores · '.config('branding.site_name'))
@section('page_title','Historial')
@section('page_description','Intentos realizados en el simulador')

@section('content')
<div class="cms-page">
    <a href="{{ route('student.quiz.index') }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver al simulador
    </a>

    <header class="cms-page-header"><div><p class="cms-eyebrow">Evaluación teórica</p><h1>Historial de intentos</h1><span>Consulta tus resultados anteriores.</span></div></header>

    <section class="cms-list-card">
        @forelse($attempts as $attempt)
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ number_format((float)$attempt->score,0) }}%</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row"><h2>{{ $attempt->module->title }}</h2><span class="cms-status {{ $attempt->score>=70?'is-published':'is-draft' }}"><span></span>{{ $attempt->resultLabel() }}</span></div>
                        <p>{{ $attempt->module->course->title }}</p>
                        <div class="course-admin-meta"><span>{{ $attempt->correct_answers }} de {{ $attempt->total_questions }} correctas</span><span>•</span><span>{{ $attempt->completed_at?->format('d/m/Y H:i') }}</span></div>
                    </div>
                </div>
                <div class="course-admin-actions"><a href="{{ route('student.quiz.result',$attempt) }}" class="cms-secondary-button">Ver resultado</a></div>
            </article>
        @empty
            <div class="cms-empty"><h2>Todavía no tienes intentos</h2><p>Realiza un simulador para ver tu historial.</p><a href="{{ route('student.quiz.index') }}" class="cms-primary-button">Ir al simulador</a></div>
        @endforelse
    </section>
</div>
@endsection