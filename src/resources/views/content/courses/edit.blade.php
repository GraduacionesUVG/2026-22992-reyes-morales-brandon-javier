@extends('layouts.app')

@section('title','Editar curso · '.config('branding.site_name'))
@section('page_title','Editar curso')
@section('page_description',$course->title)

@section('content')
<div class="cms-page cms-form-page">
    <a href="{{ route($routePrefix.'.courses.index') }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver a cursos
    </a>

    <header class="cms-form-heading"><p class="cms-eyebrow">Contenido educativo</p><h1>{{ $course->title }}</h1><span>Edita la información general y controla su publicación.</span></header>

    <form method="POST" action="{{ route($routePrefix.'.courses.update',$course) }}" class="cms-form-card">
        @csrf
        @method('PUT')

        <div class="cms-form-card-header">
            <div><h2>Información del curso</h2><p>Los cambios se reflejarán automáticamente en la plataforma.</p></div>
            <span class="cms-status {{ $course->status==='published'?'is-published':'is-draft' }}"><span></span>{{ $course->status==='published'?'Publicado':'Borrador' }}</span>
        </div>

        <div class="cms-form-card-body">@include('content.courses._form')</div>

        <footer class="cms-form-actions">
            <a href="{{ route($routePrefix.'.courses.index') }}" class="cms-cancel-button">Cancelar</a>
            <button type="submit" class="cms-primary-button">Guardar cambios</button>
        </footer>
    </form>
</div>
@endsection