@extends('layouts.app')
@section('title','Editar usuario · '.config('branding.site_name'))
@section('page_title','Editar usuario')
@section('page_description','Actualizar datos y rol del usuario')

@section('content')
<div class="cms-page cms-form-page">
    <a href="{{ route('admin.users.index') }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver a usuarios
    </a>

    <header class="cms-form-heading">
        <p class="cms-eyebrow">Administración</p>
        <h1>Editar usuario</h1>
        <span>{{ $user->name }} · {{ $user->email }}</span>
    </header>

    <form method="POST" action="{{ route('admin.users.update',$user) }}" class="cms-form-card">
        @csrf
        @method('PUT')
        @include('admin.users._form',['buttonText'=>'Guardar cambios','showPasswordHelp'=>true])
    </form>
</div>
@endsection