@extends('layouts.app')
@section('title','Módulos · '.$course->title)
@section('page_title','Módulos')
@section('page_description',$course->title)

@section('content')
<div class="cms-page">
    <a href="{{ route($routePrefix.'.courses.index') }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver a cursos
    </a>

    <header class="cms-page-header">
        <div><p class="cms-eyebrow">Curso</p><h1>{{ $course->title }}</h1><span>Organiza los módulos que conforman este curso.</span></div>
        <a href="{{ route($routePrefix.'.courses.modules.create',$course) }}" class="cms-primary-button">+ Nuevo módulo</a>
    </header>

    @if(session('status'))<div class="cms-alert-success"><span>✓</span>{{ session('status') }}</div>@endif

    <section class="cms-list-card">
        @forelse($course->modules as $module)
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ str_pad($module->sort_order,2,'0',STR_PAD_LEFT) }}</div>

                    <div class="course-admin-info">
                        <div class="course-admin-title-row">
                            <h2>{{ $module->title }}</h2>
                            <span class="cms-status {{ $module->status==='published'?'is-published':'is-draft' }}"><span></span>{{ $module->status==='published'?'Publicado':'Borrador' }}</span>
                        </div>

                        <p>{{ $module->description ?: 'Este módulo todavía no tiene descripción.' }}</p>

                        <div class="course-admin-meta">
                            <span>{{ $module->lessons_count }} {{ $module->lessons_count===1?'lección':'lecciones' }}</span>
                            <span>•</span>
                            <span>Orden {{ $module->sort_order }}</span>
                        </div>
                    </div>
                </div>

                <div class="course-admin-actions">
                    <a href="{{ route($routePrefix.'.courses.modules.lessons.index',[$course,$module]) }}" class="cms-secondary-button cms-secondary-button-accent">Gestionar lecciones</a>
                    <a href="{{ route($routePrefix.'.courses.modules.edit',[$course,$module]) }}" class="cms-secondary-button">Editar</a>

                    <form method="POST" action="{{ route($routePrefix.'.courses.modules.destroy',[$course,$module]) }}" onsubmit="return confirm('¿Deseas eliminar este módulo? También se eliminarán sus lecciones.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="cms-icon-button cms-delete-button" title="Eliminar">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M8 9V18M12 9V18M16 9V18M5 6H19M9 6V4H15V6M7 6L8 20H16L17 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="cms-empty">
                <h2>Este curso todavía no tiene módulos</h2>
                <p>Crea el primer módulo para comenzar a organizar sus lecciones.</p>
                <a href="{{ route($routePrefix.'.courses.modules.create',$course) }}" class="cms-primary-button">Crear módulo</a>
            </div>
        @endforelse
    </section>
</div>
@endsection