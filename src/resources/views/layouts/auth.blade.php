<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title',($siteSettings['site_name'] ?? config('branding.site_name','AutoEscuela Virtual')))</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    @includeIf('partials.theme')
    <style>
        body{margin:0;min-height:100vh;background:var(--cms-bg,#f8fafc);font-family:var(--cms-font,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif);color:var(--cms-text,#0f172a);}
        .auth-shell{min-height:100vh;display:grid;grid-template-columns:1.05fr .95fr;background:linear-gradient(135deg,rgba(37,99,235,.12),rgba(15,23,42,.04));}
        .auth-hero{padding:48px;display:flex;flex-direction:column;justify-content:space-between;position:relative;overflow:hidden;}
        .auth-hero::after{content:"";position:absolute;right:-120px;bottom:-120px;width:360px;height:360px;border-radius:999px;background:rgba(37,99,235,.16);}
        .auth-brand{display:flex;align-items:center;gap:14px;text-decoration:none;color:inherit;position:relative;z-index:1;}
        .auth-brand-mark{width:52px;height:52px;border-radius:18px;background:var(--cms-primary,#2563eb);display:grid;place-items:center;color:#fff;font-weight:800;box-shadow:0 18px 40px rgba(37,99,235,.28);overflow:hidden;}
        .auth-brand-mark img{width:100%;height:100%;object-fit:contain;background:#fff;padding:6px;box-sizing:border-box;}
        .auth-brand-copy strong{display:block;font-size:18px;line-height:1.1;}
        .auth-brand-copy small{display:block;color:#64748b;margin-top:4px;}
        .auth-hero-content{max-width:560px;position:relative;z-index:1;}
        .auth-eyebrow{font-size:12px;text-transform:uppercase;letter-spacing:.14em;color:var(--cms-primary,#2563eb);font-weight:800;margin:0 0 14px;}
        .auth-hero h1{font-size:48px;line-height:1.02;margin:0 0 18px;color:var(--cms-accent,#0f172a);letter-spacing:-.04em;}
        .auth-hero p{font-size:17px;line-height:1.7;color:#475569;margin:0;max-width:520px;}
        .auth-panel{display:flex;align-items:center;justify-content:center;padding:32px;background:rgba(255,255,255,.72);backdrop-filter:blur(18px);}
        .auth-card{width:min(100%,460px);background:#fff;border:1px solid rgba(15,23,42,.08);border-radius:28px;padding:34px;box-shadow:0 24px 70px rgba(15,23,42,.12);}
        .auth-card-header{margin-bottom:24px;}
        .auth-card-header h1{font-size:28px;margin:0 0 8px;color:var(--cms-accent,#0f172a);letter-spacing:-.03em;}
        .auth-card-header p{margin:0;color:#64748b;line-height:1.6;}
        .auth-footer{position:relative;z-index:1;color:#64748b;font-size:13px;}
        @media(max-width:900px){.auth-shell{grid-template-columns:1fr}.auth-hero{padding:32px 24px 18px}.auth-hero-content{margin-top:42px}.auth-hero h1{font-size:34px}.auth-panel{padding:24px}.auth-card{padding:26px;border-radius:24px}.auth-footer{display:none}}
    </style>
    @stack('styles')
    @include('partials.clarity')
</head>
<body>
    @php
        $settings=$siteSettings ?? [];
        $siteName=$settings['site_name'] ?? config('branding.site_name','AutoEscuela Virtual');
        $shortName=$settings['site_short_name'] ?? config('branding.site_short_name','AV');
        $description=$settings['site_description'] ?? 'Aprendizaje vial';
        $logoPath=$settings['logo_path'] ?? null;
        $welcomeTitle=$settings['login_title'] ?? $settings['welcome_title'] ?? 'Bienvenido a AutoEscuela Virtual';
        $welcomeSubtitle=$settings['login_subtitle'] ?? $settings['welcome_subtitle'] ?? 'Ingresa para continuar con tu aprendizaje teórico de conducción.';
    @endphp

    <main class="auth-shell">
        <section class="auth-hero">
            <a href="{{ url('/') }}" class="auth-brand">
                <span class="auth-brand-mark">
                    @if($logoPath)
                        <img src="{{ asset('storage/'.$logoPath) }}" alt="Logo {{ $siteName }}">
                    @else
                        {{ $shortName }}
                    @endif
                </span>
                <span class="auth-brand-copy">
                    <strong>{{ $siteName }}</strong>
                    <small>{{ $description }}</small>
                </span>
            </a>

            <div class="auth-hero-content">
                <p class="auth-eyebrow">Plataforma educativa</p>
                <h1>{{ $welcomeTitle }}</h1>
                <p>{{ $welcomeSubtitle }}</p>
            </div>

            <div class="auth-footer">
                © {{ date('Y') }} {{ $siteName }}. Aprendizaje teórico de conducción.
            </div>
        </section>

        <section class="auth-panel">
            <div class="auth-card">
                @yield('content')
            </div>
        </section>
    </main>

    @stack('scripts')
</body>
</html>