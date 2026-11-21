@extends('layouts.app')

@section('title','Nueva lección · '.config('branding.site_name'))

@section('page_title','Nueva lección')

@section('page_description',$module->title)

@section('content')
<div class="cms-page cms-form-page">
    <a href="{{ route($routePrefix.'.courses.modules.lessons.index',[$course,$module]) }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none">
            <path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Volver a lecciones
    </a>

    <header class="cms-form-heading">
        <p class="cms-eyebrow">Contenido educativo</p>
        <h1>Crear lección</h1>
        <span>Agrega una nueva lección al módulo {{ $module->title }}.</span>
    </header>

    @if($errors->any())
        <div class="cms-alert-danger">
            <strong>Revisa los campos del formulario.</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('status'))
        <div class="cms-alert-success">
            <span>✓</span>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route($routePrefix.'.courses.modules.lessons.store',[$course,$module]) }}" class="cms-form-card" enctype="multipart/form-data">
        @csrf

        <div class="cms-form-card-header">
            <div>
                <h2>Información de la lección</h2>
                <p>Completa únicamente lo necesario para mantener el contenido claro.</p>
            </div>
        </div>

        <div class="cms-form-card-body">
            @include('content.lessons._form')
        </div>

        <footer class="cms-form-actions">
            <a href="{{ route($routePrefix.'.courses.modules.lessons.index',[$course,$module]) }}" class="cms-cancel-button">Cancelar</a>
            <button type="submit" class="cms-primary-button">Crear lección</button>
        </footer>
    </form>
</div>
@endsection

@push('styles')
<style>
.cms-alert-danger{border:1px solid #fecaca;background:#fef2f2;color:#991b1b;border-radius:18px;padding:16px 18px;margin:18px 0}
.cms-alert-danger strong{display:block;margin-bottom:8px;color:#991b1b}
.cms-alert-danger ul{margin:0;padding-left:1.2rem}
.cms-alert-danger li{margin:.25rem 0}
</style>
@endpush