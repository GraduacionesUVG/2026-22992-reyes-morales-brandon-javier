@extends('layouts.app')
@section('title','Nuevo usuario · '.config('branding.site_name'))
@section('page_title','Nuevo usuario')
@section('page_description','Crear una cuenta para acceder a la plataforma')

@section('content')
<div class="cms-page cms-form-page">
    <a href="{{ route('admin.users.index') }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver a usuarios
    </a>

    <header class="cms-form-heading">
        <p class="cms-eyebrow">Administración</p>
        <h1>Crear usuario</h1>
        <span>Registra un usuario y asígnale un rol.</span>
    </header>

    <form method="POST" action="{{ route('admin.users.store') }}" class="cms-form-card">
        @csrf
        @include('admin.users._form',['buttonText'=>'Crear usuario','showPasswordHelp'=>false])
    </form>
</div>
@endsection