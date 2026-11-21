@extends('layouts.app')

@section('title','Nuevo curso · '.config('branding.site_name'))
@section('page_title','Nuevo curso')
@section('page_description','Crea contenido educativo para la plataforma')

@section('content')
<div class="cms-page cms-form-page">
    <a href="{{ route($routePrefix.'.courses.index') }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver a cursos
    </a>

    <header class="cms-form-heading"><p class="cms-eyebrow">Contenido educativo</p><h1>Crear curso</h1><span>Completa la información principal. Los módulos y lecciones se agregarán después.</span></header>

    <form method="POST" action="{{ route($routePrefix.'.courses.store') }}" class="cms-form-card">
        @csrf
        <div class="cms-form-card-header"><div><h2>Información del curso</h2><p>Datos principales que identificarán el curso dentro de la plataforma.</p></div></div>

        <div class="cms-form-card-body">
            @include('content.courses._form')
        </div>

        <footer class="cms-form-actions">
            <a href="{{ route($routePrefix.'.courses.index') }}" class="cms-cancel-button">Cancelar</a>
            <button type="submit" class="cms-primary-button">Crear curso</button>
        </footer>
    </form>
</div>
@endsection