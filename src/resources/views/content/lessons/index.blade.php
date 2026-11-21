@extends('layouts.app')
@section('title','Lecciones · '.config('branding.site_name'))
@section('page_title','Lecciones')
@section('page_description','Administra el contenido de aprendizaje')

@section('content')
<div class="cms-page">
    <header class="cms-page-header">
        <div><p class="cms-eyebrow">Contenido educativo</p><h1>Lecciones</h1><span>Accede a las lecciones organizadas por curso y módulo.</span></div>
    </header>

    @forelse($courses as $course)
        <section class="cms-module-course">
            <div class="cms-module-course-header">
                <div><span>Curso</span><h2>{{ $course->title }}</h2><p>{{ $course->modules->sum('lessons_count') }} {{ $course->modules->sum('lessons_count')===1?'lección':'lecciones' }}</p></div>
            </div>

            <div class="cms-module-list">
                @forelse($course->modules as $module)
                    <a href="{{ route($routePrefix.'.courses.modules.lessons.index',[$course,$module]) }}" class="cms-module-row">
                        <div class="cms-module-number">{{ str_pad($module->sort_order,2,'0',STR_PAD_LEFT) }}</div>
                        <div class="cms-module-info">
                            <div>
                                <h3>{{ $module->title }}</h3>
                                <span class="cms-status {{ $module->status==='published'?'is-published':'is-draft' }}"><span></span>{{ $module->status==='published'?'Publicado':'Borrador' }}</span>
                            </div>
                            <p>{{ $module->description ?: 'Sin descripción.' }}</p>
                            <small>{{ $module->lessons_count }} {{ $module->lessons_count===1?'lección':'lecciones' }}</small>
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