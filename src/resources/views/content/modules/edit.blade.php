@extends('layouts.app')

@section('title','Editar módulo · '.config('branding.site_name'))
@section('page_title','Editar módulo')
@section('page_description',$course->title)

@section('content')
<div class="cms-page cms-form-page">
    <a href="{{ route($routePrefix.'.courses.modules.index',$course) }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver a módulos
    </a>

    <header class="cms-form-heading">
        <p class="cms-eyebrow">Contenido educativo</p>
        <h1>{{ $module->title }}</h1>
        <span>Edita la información, publicación y posición del módulo.</span>
    </header>

    <form method="POST" action="{{ route($routePrefix.'.courses.modules.update',[$course,$module]) }}" class="cms-form-card">
        @csrf
        @method('PUT')

        <div class="cms-form-card-header">
            <div>
                <h2>Información del módulo</h2>
                <p>Los cambios se reflejarán automáticamente en el contenido del estudiante.</p>
            </div>

            <span class="cms-status {{ $module->status==='published'?'is-published':'is-draft' }}">
                <span></span>{{ $module->status==='published'?'Publicado':'Borrador' }}
            </span>
        </div>

        <div class="cms-form-card-body">@include('content.modules._form')</div>

        <footer class="cms-form-actions">
            <a href="{{ route($routePrefix.'.courses.modules.index',$course) }}" class="cms-cancel-button">Cancelar</a>
            <button type="submit" class="cms-primary-button">Guardar cambios</button>
        </footer>
    </form>
</div>
@endsection