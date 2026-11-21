@php
$user=auth()->user();
$roleLabel=match($user->role){'admin'=>'Administrador','gestor'=>'Gestor de contenido','estudiante'=>'Estudiante',default=>ucfirst($user->role)};
$contentPrefix=$user->isAdmin()?'admin':($user->isGestor()?'gestor':null);
$coursesRoute=$contentPrefix?$contentPrefix.'.courses.index':null;
$modulesRoute=$contentPrefix?$contentPrefix.'.modules.index':null;
$lessonsRoute=$contentPrefix?$contentPrefix.'.lessons.index':null;
$questionsRoute=$contentPrefix?$contentPrefix.'.questions.index':null;
$reviewRoute=$contentPrefix?$contentPrefix.'.review.index':null;
$isLessons=request()->routeIs('admin.lessons.*','gestor.lessons.*','admin.courses.modules.lessons.*','gestor.courses.modules.lessons.*');
$isQuestions=request()->routeIs('admin.questions.*','gestor.questions.*','admin.courses.modules.questions.*','gestor.courses.modules.questions.*');
$isContentReview=request()->routeIs('admin.review.*')||request()->routeIs('gestor.review.*');
$isModules=!$isLessons&&!$isQuestions&&!$isContentReview&&request()->routeIs('admin.modules.*','gestor.modules.*','admin.courses.modules.*','gestor.courses.modules.*');
$isCourses=!$isModules&&!$isLessons&&!$isQuestions&&!$isContentReview&&request()->routeIs('admin.courses.*','gestor.courses.*');
$isStudentLearning=request()->routeIs('student.modules.*','student.lessons.*');
$isStudentProgress=request()->routeIs('student.progress.*');
$isStudentFavorites=request()->routeIs('student.favorites.*');
$isStudentSearch=request()->routeIs('student.search.*');
$isStudentQuiz=request()->routeIs('student.quiz.*');
$isStudentHelp=request()->routeIs('student.help.*');
$isUsers=request()->routeIs('admin.users.*');
$isSettings=request()->routeIs('admin.settings.*');

@endphp

<aside id="appSidebar" class="app-sidebar">
    <div class="app-sidebar-header">
        <a href="{{ route('dashboard') }}" class="app-brand">
            @if(!empty($siteSettings['logo_path']))
                <span class="app-brand-mark" style="padding:0;background:#fff!important;overflow:hidden;">
                    <img src="{{ asset('storage/'.$siteSettings['logo_path']) }}" alt="Logo" style="width:100%;height:100%;object-fit:contain;padding:4px;">
                </span>
            @else
                <span class="app-brand-mark">{{ $siteSettings['site_short_name'] ?? config('branding.site_short_name','AV') }}</span>
            @endif
            <span class="app-brand-copy">
                <strong>{{ $siteSettings['site_name'] ?? config('branding.site_name','AutoEscuela Virtual') }}</strong>
                <small>{{ $siteSettings['site_description'] ?? 'Aprendizaje vial' }}</small>
            </span>
        </a>
        <button id="sidebarClose" class="app-sidebar-close" type="button" aria-label="Cerrar menú">
            <svg viewBox="0 0 24 24" fill="none"><path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
    </div>

    <nav class="app-sidebar-nav">
        <div class="app-nav-section">
            <span class="app-nav-label">Principal</span>
            <a href="{{ route('dashboard') }}" class="app-nav-link {{ request()->routeIs('dashboard','admin.dashboard','gestor.dashboard','student.dashboard')?'is-active':'' }}">
                <svg viewBox="0 0 24 24" fill="none"><path d="M4 10.5L12 4L20 10.5V19C20 19.55 19.55 20 19 20H5C4.45 20 4 19.55 4 19V10.5Z" stroke="currentColor" stroke-width="1.7"/><path d="M9 20V14H15V20" stroke="currentColor" stroke-width="1.7"/></svg>
                <span>Inicio</span>
            </a>

            @if($user->isEstudiante())
                <a href="{{ route('student.help.index') }}" class="app-nav-link {{ $isStudentHelp?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 17V17.01M9.8 9.2C10.1 7.9 11 7 12.4 7C13.8 7 15 7.9 15 9.3C15 10.5 14.3 11.1 13.4 11.7C12.5 12.3 12 12.8 12 14" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.7"/></svg>
                    <span>Guía rápida</span>
                </a>
                <a href="{{ route('student.modules.index') }}" class="app-nav-link {{ $isStudentLearning?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M5 5H10V10H5V5ZM14 5H19V10H14V5ZM5 14H10V19H5V14ZM14 14H19V19H14V14Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                    <span>Módulos</span>
                </a>
                <a href="{{ route('student.progress.index') }}" class="app-nav-link {{ $isStudentProgress?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M5 18V13M12 18V6M19 18V10" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    <span>Mi progreso</span>
                </a>
                <a href="{{ route('student.favorites.index') }}" class="app-nav-link {{ $isStudentFavorites?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M12 20C12 20 5 15.8 5 9.8C5 7.1 7.1 5 9.7 5C11.1 5 12 5.8 12 5.8C12 5.8 12.9 5 14.3 5C16.9 5 19 7.1 19 9.8C19 15.8 12 20 12 20Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg>
                    <span>Favoritos</span>
                </a>
                <a href="{{ route('student.quiz.index') }}" class="app-nav-link {{ $isStudentQuiz?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M8 4H16C17.1 4 18 4.9 18 6V20L12 17L6 20V6C6 4.9 6.9 4 8 4Z" stroke="currentColor" stroke-width="1.7"/></svg>
                    <span>Simulador</span>
                </a>
                <a href="{{ route('student.search.index') }}" class="app-nav-link {{ $isStudentSearch?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="1.7"/><path d="M16 16L20 20" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    <span>Buscar</span>
                </a>
            @endif
        </div>

        @if($user->isAdmin() || $user->isGestor())
            <div class="app-nav-section">
                <span class="app-nav-label">Contenido</span>
                <a href="{{ route($coursesRoute) }}" class="app-nav-link {{ $isCourses?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 7H20M6 4H18C19.1 4 20 4.9 20 6V18C20 19.1 19.1 20 18 20H6C4.9 20 4 19.1 4 18V6C4 4.9 4.9 4 6 4Z" stroke="currentColor" stroke-width="1.7"/></svg>
                    <span>Cursos</span>
                </a>
                <a href="{{ route($modulesRoute) }}" class="app-nav-link {{ $isModules?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M5 5H10V10H5V5ZM14 5H19V10H14V5ZM5 14H10V19H5V14ZM14 14H19V19H14V14Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                    <span>Módulos</span>
                </a>
                <a href="{{ route($lessonsRoute) }}" class="app-nav-link {{ $isLessons?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M5 4H19V20H5V4Z" stroke="currentColor" stroke-width="1.7"/><path d="M8 8H16M8 12H16M8 16H13" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    <span>Lecciones</span>
                </a>
                <a href="{{ route($questionsRoute) }}" class="app-nav-link {{ $isQuestions?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.7"/><path d="M10 9.5C10.3 8.6 11.05 8 12 8C13.1 8 14 8.9 14 10C14 11.5 12 11.7 12 13" stroke="currentColor" stroke-width="1.7"/></svg>
                    <span>Banco de preguntas</span>
                </a>
                @if($reviewRoute)
                    <a href="{{ route($reviewRoute) }}" class="app-nav-link {{ $isContentReview?'is-active':'' }}">
                        <svg viewBox="0 0 24 24" fill="none"><path d="M5 6H19M5 12H19M5 18H13" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><path d="M16 18L18 20L22 16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        <span>Revisión contenido</span>
                    </a>
                @endif
            </div>
        @endif

        @if($user->isAdmin())
            <div class="app-nav-section">
                <span class="app-nav-label">Administración</span>
                <a href="{{ route('admin.users.index') }}" class="app-nav-link {{ $isUsers?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><circle cx="9" cy="8" r="3" stroke="currentColor" stroke-width="1.7"/><path d="M4 19C4 15.7 6.2 13 9 13C11.8 13 14 15.7 14 19" stroke="currentColor" stroke-width="1.7"/><path d="M17 8V14M14 11H20" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                    <span>Usuarios</span>
                </a>
                <a href="{{ route('admin.settings.edit') }}" class="app-nav-link {{ $isSettings?'is-active':'' }}">
                    <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.7"/><path d="M19 13.5V10.5L16.8 9.8C16.6 9.3 16.3 8.8 15.9 8.4L16.4 6.1L13.8 4.6L12.1 6.2C11.5 6.1 11 6.1 10.4 6.2L8.7 4.6L6.1 6.1L6.6 8.4C6.2 8.8 5.9 9.3 5.7 9.8L3.5 10.5V13.5L5.7 14.2C5.9 14.7 6.2 15.2 6.6 15.6L6.1 17.9L8.7 19.4L10.4 17.8C11 17.9 11.5 17.9 12.1 17.8L13.8 19.4L16.4 17.9L15.9 15.6C16.3 15.2 16.6 14.7 16.8 14.2L19 13.5Z" stroke="currentColor" stroke-width="1.4"/></svg>
                    <span>Configuración CMS</span>
                </a>
            </div>
        @endif

        <div class="app-nav-section">
            <span class="app-nav-label">Cuenta</span>
            <a href="{{ route('profile.edit') }}" class="app-nav-link {{ request()->routeIs('profile.*')?'is-active':'' }}">
                <svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="8" r="3.5" stroke="currentColor" stroke-width="1.7"/><path d="M5.5 20C5.5 16.4 8.4 13.5 12 13.5C15.6 13.5 18.5 16.4 18.5 20" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>
                <span>Mi perfil</span>
            </a>
        </div>
    </nav>

    <div class="app-sidebar-footer">
        <div class="app-role-card">
            <span class="app-role-dot"></span>
            <div><small>Sesión activa</small><strong>{{ $roleLabel }}</strong></div>
        </div>
    </div>
</aside>