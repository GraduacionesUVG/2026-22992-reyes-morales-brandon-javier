@extends('layouts.app')
@section('title','Banco de preguntas · '.config('branding.site_name'))
@section('page_title','Banco de preguntas')
@section('page_description','Administra las preguntas del simulador')

@section('content')
<div class="cms-page">
    <header class="cms-page-header">
        <div><p class="cms-eyebrow">Contenido evaluativo</p><h1>Banco de preguntas</h1><span>Accede a las preguntas organizadas por curso y módulo.</span></div>
    </header>

    @forelse($courses as $course)
        <section class="cms-module-course">
            <div class="cms-module-course-header">
                <div><span>Curso</span><h2>{{ $course->title }}</h2><p>{{ $course->modules->sum('questions_count') }} {{ $course->modules->sum('questions_count')===1?'pregunta':'preguntas' }}</p></div>
            </div>

            <div class="cms-module-list">
                @forelse($course->modules as $module)
                    <a href="{{ route($routePrefix.'.courses.modules.questions.index',[$course,$module]) }}" class="cms-module-row">
                        <div class="cms-module-number">{{ str_pad($module->sort_order,2,'0',STR_PAD_LEFT) }}</div>
                        <div class="cms-module-info">
                            <div>
                                <h3>{{ $module->title }}</h3>
                                <span class="cms-status {{ $module->status==='published'?'is-published':'is-draft' }}"><span></span>{{ $module->status==='published'?'Publicado':'Borrador' }}</span>
                            </div>
                            <p>{{ $module->description ?: 'Sin descripción.' }}</p>
                            <small>{{ $module->questions_count }} {{ $module->questions_count===1?'pregunta':'preguntas' }}</small>
                        </div>
                        <svg viewBox="0 0 20 20" fill="none"><path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                @empty
                    <div class="cms-module-empty">Este curso todavía no tiene módulos.</div>
                @endforelse
            </div>
        </section>
    @empty
        <div class="cms-empty"><h2>No hay cursos disponibles</h2><p>Primero debes crear un curso y sus módulos.</p></div>
    @endforelse
</div>
@endsection