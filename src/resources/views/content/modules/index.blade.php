@extends('layouts.app')

@section('title','Módulos · '.config('branding.site_name'))
@section('page_title','Módulos')
@section('page_description','Administra la estructura del contenido educativo')

@section('content')
<div class="cms-page">
    <header class="cms-page-header">
        <div>
            <p class="cms-eyebrow">Contenido educativo</p>
            <h1>Módulos</h1>
            <span>Consulta y administra los módulos organizados dentro de cada curso.</span>
        </div>
    </header>

    @if(session('status'))
        <div class="cms-alert-success"><span>✓</span>{{ session('status') }}</div>
    @endif

    @forelse($courses as $course)
        <section class="cms-module-course">
            <div class="cms-module-course-header">
                <div>
                    <span>Curso</span>
                    <h2>{{ $course->title }}</h2>
                    <p>{{ $course->modules->count() }} {{ $course->modules->count()===1?'módulo':'módulos' }}</p>
                </div>

                <div class="cms-module-course-actions">
                    <a href="{{ route($routePrefix.'.courses.modules.index',$course) }}" class="cms-secondary-button">Gestionar módulos</a>
                    <a href="{{ route($routePrefix.'.courses.modules.create',$course) }}" class="cms-primary-button">+ Nuevo módulo</a>
                </div>
            </div>

            <div class="cms-module-list">
                @forelse($course->modules as $module)
                    <a href="{{ route($routePrefix.'.courses.modules.edit',[$course,$module]) }}" class="cms-module-row">
                        <div class="cms-module-number">{{ str_pad($module->sort_order,2,'0',STR_PAD_LEFT) }}</div>

                        <div class="cms-module-info">
                            <div>
                                <h3>{{ $module->title }}</h3>
                                <span class="cms-status {{ $module->status==='published'?'is-published':'is-draft' }}">
                                    <span></span>{{ $module->status==='published'?'Publicado':'Borrador' }}
                                </span>
                            </div>

                            <p>{{ $module->description ?: 'Sin descripción.' }}</p>

                            <small>
                                {{ $module->lessons_count }}
                                {{ $module->lessons_count===1?'lección':'lecciones' }}
                            </small>
                        </div>

                        <svg viewBox="0 0 20 20" fill="none"><path d="M7 4L13 10L7 16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                @empty
                    <div class="cms-module-empty">Este curso todavía no tiene módulos.</div>
                @endforelse
            </div>
        </section>
    @empty
        <div class="cms-empty">
            <h2>No hay cursos disponibles</h2>
            <p>Primero debes crear un curso para poder agregar módulos.</p>
        </div>
    @endforelse
</div>
@endsection