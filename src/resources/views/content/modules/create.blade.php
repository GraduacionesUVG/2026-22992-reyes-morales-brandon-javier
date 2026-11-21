@extends('layouts.app')

@section('title','Nuevo módulo · '.config('branding.site_name'))
@section('page_title','Nuevo módulo')
@section('page_description',$course->title)

@section('content')
<div class="cms-page cms-form-page">
    <a href="{{ route($routePrefix.'.courses.modules.index',$course) }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver a módulos
    </a>

    <header class="cms-form-heading">
        <p class="cms-eyebrow">Contenido educativo</p>
        <h1>Crear módulo</h1>
        <span>Agrega un nuevo módulo al curso {{ $course->title }}.</span>
    </header>

    <form method="POST" action="{{ route($routePrefix.'.courses.modules.store',$course) }}" class="cms-form-card">
        @csrf

        <div class="cms-form-card-header">
            <div>
                <h2>Información del módulo</h2>
                <p>Las lecciones podrán agregarse posteriormente.</p>
            </div>
        </div>

        <div class="cms-form-card-body">@include('content.modules._form')</div>

        <footer class="cms-form-actions">
            <a href="{{ route($routePrefix.'.courses.modules.index',$course) }}" class="cms-cancel-button">Cancelar</a>
            <button type="submit" class="cms-primary-button">Crear módulo</button>
        </footer>
    </form>
</div>
@endsection