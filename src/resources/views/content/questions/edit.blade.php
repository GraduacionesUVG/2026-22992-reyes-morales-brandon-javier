@extends('layouts.app')
@section('title','Editar pregunta · '.config('branding.site_name'))
@section('page_title','Editar pregunta')
@section('page_description',$module->title)

@section('content')
<div class="cms-page cms-form-page">
    <a href="{{ route($routePrefix.'.courses.modules.questions.index',[$course,$module]) }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none">
            <path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Volver a preguntas
    </a>

    <header class="cms-form-heading">
        <p class="cms-eyebrow">Banco de preguntas</p>
        <h1>Editar pregunta</h1>
        <span>Actualiza la pregunta, opciones, respuesta correcta y retroalimentación.</span>
    </header>

    @if(session('status'))<div class="cms-alert-success"><span>✓</span>{{ session('status') }}</div>@endif

    <form method="POST" action="{{ route($routePrefix.'.courses.modules.questions.update',[$course,$module,$question]) }}" class="cms-form-card">
        @csrf @method('PUT')
        <div class="cms-form-card-header">
            <div>
                <h2>Información de la pregunta</h2>
                <p>Los cambios se reflejarán en el simulador cuando la pregunta esté publicada.</p>
            </div>
        </div>
        <div class="cms-form-card-body">@include('content.questions._form')</div>
        <footer class="cms-form-actions">
            <a href="{{ route($routePrefix.'.courses.modules.questions.index',[$course,$module]) }}" class="cms-cancel-button">Cancelar</a>
            <button type="submit" class="cms-primary-button">Guardar cambios</button>
        </footer>
    </form>
</div>
@endsection