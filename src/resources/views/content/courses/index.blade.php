@extends('layouts.app')

@section('title','Cursos · '.config('branding.site_name'))
@section('page_title','Cursos')
@section('page_description','Administra el contenido educativo de la plataforma')

@section('content')
<div class="cms-page">
    <header class="cms-page-header">
        <div><p class="cms-eyebrow">Contenido educativo</p><h1>Cursos</h1><span>Organiza, publica y administra los cursos disponibles para los estudiantes.</span></div>
        <a href="{{ route($routePrefix.'.courses.create') }}" class="cms-primary-button">
            <svg viewBox="0 0 20 20" fill="none"><path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
            Nuevo curso
        </a>
    </header>

    @if(session('status'))
        <div class="cms-alert-success"><span>✓</span>{{ session('status') }}</div>
    @endif

    <div class="cms-summary">
        <div><span>Total</span><strong>{{ $courses->count() }}</strong></div>
        <div><span>Publicados</span><strong>{{ $courses->where('status','published')->count() }}</strong></div>
        <div><span>Borradores</span><strong>{{ $courses->where('status','draft')->count() }}</strong></div>
    </div>

    <section class="cms-list-card">
        @forelse($courses as $course)
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ str_pad($course->sort_order,2,'0',STR_PAD_LEFT) }}</div>

                    <div class="course-admin-info">
                        <div class="course-admin-title-row">
                            <h2>{{ $course->title }}</h2>
                            <span class="cms-status {{ $course->status==='published'?'is-published':'is-draft' }}">
                                <span></span>{{ $course->status==='published'?'Publicado':'Borrador' }}
                            </span>
                        </div>

                        <p>{{ $course->description ?: 'Este curso todavía no tiene descripción.' }}</p>

                        <div class="course-admin-meta">
                            <span>{{ $course->modules_count }} {{ $course->modules_count===1?'módulo':'módulos' }}</span>
                            <span>•</span>
                            <span>{{ $course->lessons_count }} {{ $course->lessons_count===1?'lección':'lecciones' }}</span>
                            <span>•</span>
                            <span>Orden {{ $course->sort_order }}</span>
                        </div>
                    </div>
                </div>

                <div class="course-admin-actions">
                    <a href="{{ route($routePrefix.'.courses.modules.index',$course) }}" class="cms-secondary-button">Módulos</a>
                    <a href="{{ route($routePrefix.'.courses.edit',$course) }}" class="cms-secondary-button">Editar</a>

                    <form method="POST" action="{{ route($routePrefix.'.courses.destroy',$course) }}" onsubmit="return confirm('¿Deseas eliminar este curso? También se eliminarán sus módulos y lecciones.')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="cms-icon-button cms-delete-button" title="Eliminar">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M8 9V18M12 9V18M16 9V18M5 6H19M9 6V4H15V6M7 6L8 20H16L17 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="cms-empty">
                <div class="cms-empty-icon"><svg viewBox="0 0 24 24" fill="none"><path d="M5 4H19V20H5V4Z" stroke="currentColor" stroke-width="1.6"/><path d="M8 8H16M8 12H16M8 16H13" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></div>
                <h2>No hay cursos todavía</h2>
                <p>Crea el primer curso para comenzar a organizar el contenido educativo.</p>
                <a href="{{ route($routePrefix.'.courses.create') }}" class="cms-primary-button">Crear curso</a>
            </div>
        @endforelse
    </section>
</div>
@endsection