
@php
    $user = auth()->user();
    $words = preg_split('/\s+/', trim($user->name));
    $initials = collect($words)->filter()->take(2)->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))->implode('');
    $roleLabel = match($user->role) {
        'admin' => 'Administrador',
        'gestor' => 'Gestor de contenido',
        'estudiante' => 'Estudiante',
        default => ucfirst($user->role),
    };
@endphp

<header class="app-header">
    <div class="app-header-left">
        <button id="sidebarToggle" class="app-mobile-menu" type="button" aria-label="Abrir menú" aria-controls="appSidebar" aria-expanded="false">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 7H20M4 12H20M4 17H20" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
        <div class="app-page-heading">
            <h1>@yield('page_title', 'Inicio')</h1>
            @hasSection('page_description')
                <p>@yield('page_description')</p>
            @endif
        </div>
    </div>

    <div class="app-header-actions">
        <a href="{{ route('profile.edit') }}" class="app-user-button" aria-label="Abrir perfil">
            <span class="app-user-avatar">{{ $initials ?: 'AV' }}</span>
            <span class="app-user-info">
                <strong>{{ $user->name }}</strong>
                <small>{{ $roleLabel }}</small>
            </span>
            <svg class="app-user-chevron" viewBox="0 0 20 20" fill="none" aria-hidden="true"><path d="M7 8L10 11L13 8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>

        <form method="POST" action="{{ route('logout') }}" data-clarity-event="logout_requested">
            @csrf
            <button type="submit" class="app-logout-button" aria-label="Cerrar sesión" title="Cerrar sesión">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M9 5H6.5C5.12 5 4 6.12 4 7.5V16.5C4 17.88 5.12 19 6.5 19H9M14 8L18 12L14 16M18 12H9" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </form>
    </div>
</header>
