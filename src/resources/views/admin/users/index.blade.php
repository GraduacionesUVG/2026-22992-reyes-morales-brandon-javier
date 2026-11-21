@extends('layouts.app')
@section('title','Usuarios · '.config('branding.site_name'))
@section('page_title','Usuarios')
@section('page_description','Gestión de usuarios y roles de la plataforma')

@section('content')
<div class="cms-page">
    <header class="cms-page-header">
        <div><p class="cms-eyebrow">Administración</p><h1>Usuarios</h1><span>Administra estudiantes, gestores de contenido y administradores.</span></div>
        <a href="{{ route('admin.users.create') }}" class="cms-primary-button">Nuevo usuario</a>
    </header>

    @if(session('status'))<div class="cms-alert-success"><span>✓</span>{{ session('status') }}</div>@endif

    <section class="student-home-stats">
        <div class="student-home-stat"><span>Total</span><strong>{{ $counts['total'] }}</strong></div>
        <div class="student-home-stat"><span>Administradores</span><strong>{{ $counts['admin'] }}</strong></div>
        <div class="student-home-stat"><span>Gestores</span><strong>{{ $counts['gestor'] }}</strong></div>
        <div class="student-home-stat"><span>Estudiantes</span><strong>{{ $counts['estudiante'] }}</strong></div>
    </section>

    <form method="GET" action="{{ route('admin.users.index') }}" class="cms-form-card" style="margin-top:24px;margin-bottom:24px;">
        <div class="cms-form-card-body">
            <div class="cms-form-grid">
                <div class="cms-field">
                    <label for="q">Buscar</label>
                    <input id="q" name="q" type="search" value="{{ $search }}" placeholder="Nombre o correo">
                </div>
                <div class="cms-field">
                    <label for="role">Rol</label>
                    <select id="role" name="role">
                        <option value="">Todos los roles</option>
                        @foreach($roles as $value=>$label)
                            <option value="{{ $value }}" @selected($role===$value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <footer class="cms-form-actions">
            <a href="{{ route('admin.users.index') }}" class="cms-cancel-button">Limpiar</a>
            <button type="submit" class="cms-primary-button">Filtrar</button>
        </footer>
    </form>

    <section class="cms-list-card">
        @forelse($users as $userItem)
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ strtoupper(substr($userItem->name,0,1)) }}</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row">
                            <h2>{{ $userItem->name }}</h2>
                            <span class="cms-status {{ $userItem->role==='estudiante'?'is-published':'is-draft' }}"><span></span>{{ $roles[$userItem->role] ?? ucfirst($userItem->role) }}</span>
                        </div>
                        <p>{{ $userItem->email }}</p>
                        <div class="course-admin-meta">
                            <span>Creado: {{ $userItem->created_at?->format('d/m/Y H:i') }}</span>
                            @if($userItem->id===auth()->id())<span>•</span><span>Usuario actual</span>@endif
                        </div>
                    </div>
                </div>
                <div class="course-admin-actions">
                    <a href="{{ route('admin.users.edit',$userItem) }}" class="cms-secondary-button">Editar</a>
                    @if($userItem->id!==auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy',$userItem) }}" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="cms-danger-button">Eliminar</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="cms-empty"><h2>No se encontraron usuarios</h2><p>Prueba con otro filtro o crea un nuevo usuario.</p></div>
        @endforelse
    </section>

    <div style="margin-top:18px;">{{ $users->links() }}</div>
</div>
@endsection