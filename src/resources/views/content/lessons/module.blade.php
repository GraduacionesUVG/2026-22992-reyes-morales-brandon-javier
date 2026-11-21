@extends('layouts.app')
@section('title','Lecciones · '.$module->title)
@section('page_title','Lecciones')
@section('page_description',$module->title)

@section('content')
<div class="cms-page">
    <a href="{{ route($routePrefix.'.courses.modules.index',$course) }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver a módulos
    </a>

    <header class="cms-page-header">
        <div><p class="cms-eyebrow">{{ $course->title }}</p><h1>{{ $module->title }}</h1><span>Organiza el contenido educativo de este módulo.</span></div>
        <a href="{{ route($routePrefix.'.courses.modules.lessons.create',[$course,$module]) }}" class="cms-primary-button">+ Nueva lección</a>
    </header>

    @if(session('status'))<div class="cms-alert-success"><span>✓</span>{{ session('status') }}</div>@endif

    <section class="cms-list-card">
        @forelse($module->lessons as $lesson)
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ str_pad($lesson->sort_order,2,'0',STR_PAD_LEFT) }}</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row">
                            <h2>{{ $lesson->title }}</h2>
                            <span class="cms-status {{ $lesson->status==='published'?'is-published':'is-draft' }}"><span></span>{{ $lesson->status==='published'?'Publicado':'Borrador' }}</span>
                        </div>
                        <p>{{ $lesson->summary ?: 'Esta lección todavía no tiene resumen.' }}</p>
                        <div class="course-admin-meta">
                            @if($lesson->estimated_minutes)<span>{{ $lesson->estimated_minutes }} min</span><span>•</span>@endif
                            <span>Orden {{ $lesson->sort_order }}</span>
                            @if($lesson->video_url)<span>•</span><span>Video</span>@endif
                        </div>
                    </div>
                </div>

                <div class="course-admin-actions">
                    <a href="{{ route($routePrefix.'.courses.modules.lessons.edit',[$course,$module,$lesson]) }}" class="cms-secondary-button">Editar</a>
                    <form method="POST" action="{{ route($routePrefix.'.courses.modules.lessons.destroy',[$course,$module,$lesson]) }}" onsubmit="return confirm('¿Deseas eliminar esta lección?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="cms-icon-button cms-delete-button" title="Eliminar">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M8 9V18M12 9V18M16 9V18M5 6H19M9 6V4H15V6M7 6L8 20H16L17 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="cms-empty">
                <h2>Este módulo todavía no tiene lecciones</h2>
                <p>Crea la primera lección para comenzar a desarrollar el contenido.</p>
                <a href="{{ route($routePrefix.'.courses.modules.lessons.create',[$course,$module]) }}" class="cms-primary-button">Crear lección</a>
            </div>
        @endforelse
    </section>
</div>
@endsection