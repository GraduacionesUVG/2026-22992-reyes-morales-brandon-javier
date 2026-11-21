@extends('layouts.app')
@section('title','Favoritos · '.config('branding.site_name'))
@section('page_title','Favoritos')
@section('page_description','Lecciones guardadas para repasar después')

@section('content')
<div class="cms-page">
    <header class="cms-page-header">
        <div>
            <p class="cms-eyebrow">Repaso personal</p>
            <h1>Mis favoritos</h1>
            <span>Consulta las lecciones que guardaste para revisar más adelante.</span>
        </div>
        <a href="{{ route('student.modules.index') }}" class="cms-secondary-button">Ver módulos</a>
    </header>

    @if(session('status'))<div class="cms-alert-success"><span>✓</span>{{ session('status') }}</div>@endif

    <section class="cms-list-card">
        @forelse($favorites as $favorite)
            @php $lesson=$favorite->lesson; @endphp
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ str_pad($lesson->sort_order,2,'0',STR_PAD_LEFT) }}</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row">
                            <h2>{{ $lesson->title }}</h2>
                            <span class="cms-status is-published"><span></span>Favorito</span>
                        </div>
                        <p>{{ $lesson->summary ?: 'Lección guardada para repaso.' }}</p>
                        <div class="course-admin-meta">
                            <span>{{ $lesson->module->title }}</span>
                            <span>•</span>
                            <span>{{ $lesson->module->course->title }}</span>
                            <span>•</span>
                            <span>Guardada {{ $favorite->created_at?->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
                <div class="course-admin-actions">
                    <a href="{{ route('student.lessons.show',$lesson) }}" class="cms-secondary-button">Abrir</a>
                    <form method="POST" action="{{ route('student.favorites.destroy',$favorite) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cms-danger-button">Quitar</button>
                    </form>
                </div>
            </article>
        @empty
            <div class="cms-empty">
                <h2>No tienes favoritos todavía</h2>
                <p>Abre una lección y presiona “Agregar a favoritos” para guardarla aquí.</p>
                <a href="{{ route('student.modules.index') }}" class="cms-primary-button">Explorar módulos</a>
            </div>
        @endforelse
    </section>
</div>
@endsection