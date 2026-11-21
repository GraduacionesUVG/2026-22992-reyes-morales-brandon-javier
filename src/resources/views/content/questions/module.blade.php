@extends('layouts.app')
@section('title','Preguntas · '.$module->title)
@section('page_title','Preguntas')
@section('page_description',$module->title)

@section('content')
<div class="cms-page">
    <a href="{{ route($routePrefix.'.questions.index') }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver al banco de preguntas
    </a>

    <header class="cms-page-header">
        <div>
            <p class="cms-eyebrow">{{ $course->title }}</p>
            <h1>{{ $module->title }}</h1>
            <span>Crea y administra las preguntas del simulador para este módulo.</span>
        </div>
        <a href="{{ route($routePrefix.'.courses.modules.questions.create',[$course,$module]) }}" class="cms-primary-button">+ Nueva pregunta</a>
    </header>

    @if(session('status'))
        <div class="cms-alert-success"><span>✓</span>{{ session('status') }}</div>
    @endif

    <section class="cms-list-card">
        @forelse($module->questions as $question)
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ str_pad($question->sort_order,2,'0',STR_PAD_LEFT) }}</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row">
                            <h2>{{ $question->question_text }}</h2>
                            <span class="cms-status {{ $question->status==='published'?'is-published':'is-draft' }}"><span></span>{{ $question->status==='published'?'Publicado':'Borrador' }}</span>
                        </div>
                        <p>{{ $question->explanation ?: 'Sin retroalimentación registrada.' }}</p>
                        <div class="course-admin-meta">
                            <span>{{ $question->options_count }} opciones</span>
                            <span>•</span>
                            <span>{{ $question->correct_options_count }} correcta</span>
                            <span>•</span>
                            <span>{{ $question->points }} punto{{ $question->points===1?'':'s' }}</span>
                        </div>
                    </div>
                </div>

                <div class="course-admin-actions">
                    <a href="{{ route($routePrefix.'.courses.modules.questions.edit',[$course,$module,$question]) }}" class="cms-secondary-button">Editar</a>
                    <form method="POST" action="{{ route($routePrefix.'.courses.modules.questions.destroy',[$course,$module,$question]) }}" onsubmit="return confirm('¿Deseas eliminar esta pregunta?')">
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
                <h2>Este módulo todavía no tiene preguntas</h2>
                <p>Crea preguntas con 4 opciones y una respuesta correcta.</p>
                <a href="{{ route($routePrefix.'.courses.modules.questions.create',[$course,$module]) }}" class="cms-primary-button">Crear pregunta</a>
            </div>
        @endforelse
    </section>
</div>
@endsection