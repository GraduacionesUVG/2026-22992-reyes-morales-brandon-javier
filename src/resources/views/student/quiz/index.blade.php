@extends('layouts.app')
@section('title','Simulador · '.config('branding.site_name'))
@section('page_title','Simulador')
@section('page_description','Practica con preguntas publicadas por módulo')

@section('content')
<div class="cms-page">
    <header class="cms-page-header">
        <div><p class="cms-eyebrow">Evaluación teórica</p><h1>Simulador</h1><span>Selecciona un módulo para responder sus preguntas.</span></div>
        <a href="{{ route('student.quiz.history') }}" class="cms-secondary-button">Ver historial</a>
    </header>

    @if(session('status'))<div class="cms-alert-success"><span>!</span>{{ session('status') }}</div>@endif

    <section class="cms-list-card">
        @forelse($modules as $module)
            @php $attempt=$latestAttempts->get($module->id); @endphp
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ str_pad($module->sort_order,2,'0',STR_PAD_LEFT) }}</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row"><h2>{{ $module->title }}</h2><span class="cms-status is-published"><span></span>{{ $module->published_questions_count }} preguntas</span></div>
                        <p>{{ $module->description ?: 'Practica los contenidos de este módulo.' }}</p>
                        <div class="course-admin-meta">
                            <span>{{ $module->course->title }}</span>
                            @if($attempt)<span>•</span><span>Último resultado: {{ number_format((float)$attempt->score,2) }}%</span>@endif
                        </div>
                    </div>
                </div>
                <div class="course-admin-actions"><a href="{{ route('student.quiz.show',$module) }}" class="cms-primary-button">Iniciar simulador</a></div>
            </article>
        @empty
            <div class="cms-empty"><h2>No hay simuladores disponibles</h2><p>Debe existir al menos un curso, módulo y preguntas publicadas.</p></div>
        @endforelse
    </section>
</div>
@endsection