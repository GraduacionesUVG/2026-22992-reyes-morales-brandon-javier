
@php
    $clarityId = config('services.clarity.project_id');
    $clarityEnabled = config('services.clarity.enabled', false) && filled($clarityId);
    $clarityUser = auth()->user();
    $clarityRoute = \Illuminate\Support\Facades\Route::currentRouteName() ?: 'sin_ruta';

    $claritySection = match(true) {
        $clarityRoute === 'login' => 'login',
        $clarityRoute === 'register' => 'registro',
        str_starts_with($clarityRoute, 'password.') => 'recuperacion',
        str_contains($clarityRoute, 'dashboard') => 'dashboard',
        str_contains($clarityRoute, 'courses') => 'cursos',
        str_contains($clarityRoute, 'modules') => 'modulos',
        str_contains($clarityRoute, 'lessons') => 'lecciones',
        str_contains($clarityRoute, 'questions') => 'preguntas',
        str_contains($clarityRoute, 'quiz') => 'simulador',
        str_contains($clarityRoute, 'favorites') => 'favoritos',
        str_contains($clarityRoute, 'progress') => 'progreso',
        str_contains($clarityRoute, 'search') => 'busqueda',
        str_contains($clarityRoute, 'profile') => 'perfil',
        str_contains($clarityRoute, 'help') => 'ayuda',
        str_contains($clarityRoute, 'review') => 'revision',
        str_contains($clarityRoute, 'settings') => 'configuracion',
        str_contains($clarityRoute, 'users') => 'usuarios',
        default => 'otra'
    };

    $clarityPage = [$clarityRoute];
    $clarityResources = [];

    foreach (['course', 'module', 'lesson', 'question', 'attempt'] as $key) {
        $value = request()->route($key);

        if ($value instanceof \Illuminate\Database\Eloquent\Model) {
            $value = $value->getRouteKey();
        }

        if (is_scalar($value) && (string) $value !== '') {
            $safeValue = (string) $value;

            if (preg_match('/^[a-zA-Z0-9_-]{1,80}$/', $safeValue)) {
                $clarityResources[$key] = $safeValue;
                $clarityPage[] = $key.'='.$safeValue;
            }
        }
    }

    $clarityAuthError = $clarityRoute === 'login'
        && isset($errors)
        && $errors->any();
@endphp

@if($clarityEnabled)
@once
<script>
(function(c,l,a,r,i,t,y){
    c[a]=c[a]||function(){
        (c[a].q=c[a].q||[]).push(arguments);
    };
    t=l.createElement(r);
    t.async=1;
    t.src='https://www.clarity.ms/tag/'+i;
    y=l.getElementsByTagName(r)[0];
    y.parentNode.insertBefore(t,y);
})(window,document,'clarity','script',@json($clarityId));

window.tgClarityContext = {
    authenticated: @json((bool) $clarityUser),
    userId: @json($clarityUser ? 'user-'.$clarityUser->id : null),
    friendlyName: @json(
        $clarityUser && config('services.clarity.send_friendly_name', false)
            ? $clarityUser->name
            : null
    ),
    role: @json($clarityUser?->role ?: 'invitado'),
    section: @json($claritySection),
    route: @json($clarityRoute),
    pageId: @json(implode('|', $clarityPage)),
    resources: @json($clarityResources),
    environment: @json(app()->environment()),
    loginFailed: @json($clarityAuthError)
};
</script>
<script defer src="{{ asset('js/clarity-tracking.js') }}"></script>
@endonce
@endif
