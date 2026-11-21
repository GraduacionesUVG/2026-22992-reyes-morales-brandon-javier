@php
    use App\Models\SiteSetting;

    $settings=SiteSetting::allSettings();
    $siteName=$settings['site_name'] ?? 'AutoEscuela Virtual';
    $shortName=$settings['site_short_name'] ?? 'AV';
    $description=$settings['site_description'] ?? 'Plataforma web para el aprendizaje teórico de conducción.';
    $logoPath=$settings['logo_path'] ?? null;
    $loginImagePath=$settings['login_image_path'] ?? null;
    $primary=$settings['primary_color'] ?? '#0d6efd';
    $accent=$settings['accent_color'] ?? '#1e3a8a';
    $background=$settings['background_color'] ?? '#f8fafc';
    $text=$settings['text_color'] ?? '#0f172a';
    $font=$settings['font_family'] ?? 'Inter';

    $features=array_values(array_filter([
        $settings['login_feature_1'] ?? null,
        $settings['login_feature_2'] ?? null,
        $settings['login_feature_3'] ?? null,
        $settings['login_feature_4'] ?? null,
    ]));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings['login_title'] ?? 'Iniciar sesión' }} · {{ $siteName }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        :root{
            --authx-primary:{{ $primary }};
            --authx-accent:{{ $accent }};
            --authx-bg:{{ $background }};
            --authx-text:{{ $text }};
            --authx-font:"{{ $font }}",-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
        }

        *{box-sizing:border-box}

        html,body{
            margin:0;
            min-height:100%;
            font-family:var(--authx-font);
            background:#eef4ff;
            color:var(--authx-text);
        }

        body{
            min-height:100vh;
            overflow-x:hidden;
        }

        .authx-page{
            min-height:100vh;
            width:100%;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:32px;
            background:
                radial-gradient(circle at 15% 15%,rgba(13,110,253,.10),transparent 32%),
                linear-gradient(135deg,#eef4ff 0%,#f8fbff 55%,#ffffff 100%);
        }

        .authx-shell{
            width:100%;
            max-width:1180px;
            min-height:680px;
            display:grid;
            grid-template-columns:1fr 1fr;
            overflow:hidden;
            border-radius:34px;
            background:#fff;
            border:1px solid rgba(15,23,42,.08);
            box-shadow:0 28px 80px rgba(15,23,42,.14);
        }

        .authx-left{
            position:relative;
            overflow:hidden;
            display:flex;
            flex-direction:column;
            justify-content:space-between;
            padding:44px;
            background:linear-gradient(160deg,#eaf2ff 0%,#dbeafe 100%);
        }

        .authx-left::before{
            content:"";
            position:absolute;
            right:-130px;
            bottom:-160px;
            width:400px;
            height:400px;
            border-radius:999px;
            background:rgba(13,110,253,.14);
        }

        .authx-bg-image{
            position:absolute;
            inset:0;
            background-size:cover;
            background-position:center;
            opacity:.13;
            pointer-events:none;
        }

        .authx-brand,
        .authx-copy,
        .authx-footer{
            position:relative;
            z-index:1;
        }

        .authx-brand{
            display:flex;
            align-items:center;
            gap:14px;
            text-decoration:none;
            color:inherit;
        }

        .authx-logo{
            width:58px;
            height:58px;
            border-radius:18px;
            display:grid;
            place-items:center;
            flex:0 0 auto;
            overflow:hidden;
            color:#fff;
            background:var(--authx-primary);
            font-weight:900;
            box-shadow:0 18px 38px rgba(13,110,253,.28);
        }

        .authx-logo img{
            width:100%;
            height:100%;
            object-fit:contain;
            padding:7px;
            background:#fff;
        }

        .authx-brand-text strong{
            display:block;
            color:#0f172a;
            font-size:18px;
            line-height:1.1;
            font-weight:900;
        }

        .authx-brand-text small{
            display:block;
            margin-top:5px;
            max-width:390px;
            color:#64748b;
            font-size:11px;
            line-height:1.35;
            font-weight:800;
            text-transform:uppercase;
            letter-spacing:.08em;
        }

        .authx-copy{
            max-width:540px;
        }

        .authx-badge{
            display:inline-flex;
            align-items:center;
            max-width:100%;
            padding:9px 14px;
            border-radius:999px;
            background:rgba(13,110,253,.12);
            color:var(--authx-primary);
            font-size:12px;
            font-weight:900;
            letter-spacing:.14em;
            text-transform:uppercase;
        }

        .authx-copy h1{
            margin:22px 0 16px;
            color:#0f172a;
            font-size:clamp(38px,4vw,56px);
            line-height:1.03;
            letter-spacing:-.055em;
            font-weight:950;
        }

        .authx-copy p{
            margin:0;
            color:#475569;
            font-size:17px;
            line-height:1.75;
        }

        .authx-features{
            display:grid;
            gap:12px;
            margin-top:28px;
        }

        .authx-feature{
            display:flex;
            align-items:center;
            gap:12px;
            color:#334155;
            font-size:15px;
            font-weight:800;
        }

        .authx-feature span{
            width:26px;
            height:26px;
            border-radius:999px;
            display:grid;
            place-items:center;
            flex:0 0 auto;
            background:rgba(13,110,253,.12);
            color:var(--authx-primary);
            font-size:14px;
        }

        .authx-footer{
            max-width:520px;
            color:#64748b;
            font-size:13px;
            line-height:1.45;
        }

        .authx-right{
            display:flex;
            align-items:center;
            justify-content:center;
            padding:48px;
            background:linear-gradient(180deg,#ffffff 0%,#f8fbff 100%);
        }

        .authx-card{
            width:100%;
            max-width:460px;
            padding:38px;
            border-radius:30px;
            background:#fff;
            border:1px solid rgba(15,23,42,.08);
            box-shadow:0 24px 70px rgba(15,23,42,.12);
        }

        .authx-card-header{
            margin-bottom:26px;
        }

        .authx-card-header .authx-card-badge{
            margin:0 0 10px;
            color:var(--authx-primary);
            font-size:12px;
            font-weight:900;
            letter-spacing:.14em;
            text-transform:uppercase;
        }

        .authx-card-header h2{
            margin:0 0 10px;
            color:#0f172a;
            font-size:32px;
            line-height:1.14;
            letter-spacing:-.04em;
            font-weight:950;
        }

        .authx-card-header p{
            margin:0;
            color:#64748b;
            font-size:15px;
            line-height:1.65;
        }

        .authx-form{
            display:grid;
            gap:16px;
            width:100%;
        }

        .authx-field{
            display:grid;
            gap:8px;
            width:100%;
        }

        .authx-field label{
            color:#334155;
            font-size:14px;
            font-weight:800;
        }

        .authx-field input{
            width:100%;
            min-width:0;
            height:54px;
            border:1px solid rgba(15,23,42,.14);
            border-radius:15px;
            padding:0 15px;
            background:#fff;
            color:#0f172a;
            font:inherit;
            outline:none;
            font-size:15px;
        }

        .authx-field input:focus{
            border-color:var(--authx-primary);
            box-shadow:0 0 0 4px rgba(13,110,253,.12);
        }

        .authx-row{
            width:100%;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:14px;
            flex-wrap:wrap;
        }

        .authx-check{
            display:flex;
            align-items:center;
            gap:9px;
            color:#475569;
            font-size:14px;
            font-weight:600;
        }

        .authx-check input{
            width:16px;
            height:16px;
            margin:0;
        }

        .authx-link{
            color:var(--authx-primary);
            font-size:14px;
            font-weight:850;
            text-decoration:none;
        }

        .authx-link:hover{
            text-decoration:underline;
            text-underline-offset:3px;
        }

        .authx-button{
            width:100%;
            min-height:54px;
            border:0;
            border-radius:16px;
            padding:14px 16px;
            background:var(--authx-primary);
            color:#fff;
            font:inherit;
            font-weight:900;
            cursor:pointer;
            box-shadow:0 18px 34px rgba(13,110,253,.22);
        }

        .authx-button:hover{
            filter:brightness(.96);
        }

        .authx-error{
            margin:0;
            color:#b91c1c;
            font-size:13px;
            font-weight:650;
        }

        .authx-status{
            margin-bottom:16px;
            padding:12px 14px;
            border-radius:14px;
            background:#ecfdf5;
            color:#047857;
            font-weight:700;
        }

        .authx-bottom{
            margin:20px 0 0;
            text-align:center;
            color:#64748b;
            font-size:14px;
        }

        @media(max-width:980px){
            .authx-page{
                align-items:flex-start;
                padding:20px;
            }

            .authx-shell{
                grid-template-columns:1fr;
                min-height:auto;
            }

            .authx-left{
                padding:32px;
                gap:42px;
            }

            .authx-right{
                padding:32px;
            }

            .authx-card{
                max-width:100%;
            }
        }

        @media(max-width:560px){
            .authx-page{
                padding:0;
                background:#fff;
            }

            .authx-shell{
                border-radius:0;
                border:0;
                box-shadow:none;
            }

            .authx-left{
                padding:24px;
            }

            .authx-logo{
                width:50px;
                height:50px;
                border-radius:16px;
            }

            .authx-copy h1{
                font-size:35px;
            }

            .authx-right{
                padding:24px;
            }

            .authx-card{
                padding:24px;
                border-radius:24px;
            }

            .authx-row{
                align-items:flex-start;
                flex-direction:column;
                gap:10px;
            }
        }
    </style>
    @include('partials.clarity')    
</head>

<body>
    <main class="authx-page">
        <section class="authx-shell">

            <aside class="authx-left">
                @if($loginImagePath)
                    <div class="authx-bg-image" style="background-image:url('{{ asset('storage/'.$loginImagePath) }}')"></div>
                @endif

                <a href="{{ url('/') }}" class="authx-brand">
                    <span class="authx-logo">
                        @if($logoPath)
                            <img src="{{ asset('storage/'.$logoPath) }}" alt="Logo {{ $siteName }}">
                        @else
                            {{ $shortName }}
                        @endif
                    </span>

                    <span class="authx-brand-text">
                        <strong>{{ $siteName }}</strong>
                        <small>{{ $description }}</small>
                    </span>
                </a>

                <div class="authx-copy">
                    <span class="authx-badge">{{ $settings['login_badge'] ?? 'Plataforma educativa' }}</span>

                    <h1>{{ $settings['login_side_title'] ?? 'Aprende teoría vial de forma clara, segura y organizada' }}</h1>

                    <p>{{ $settings['login_side_subtitle'] ?? 'Estudia módulos, revisa lecciones, consulta recursos de apoyo y practica con el simulador de preguntas.' }}</p>

                    @if(count($features))
                        <div class="authx-features">
                            @foreach($features as $feature)
                                <div class="authx-feature">
                                    <span>✓</span>
                                    <strong>{{ $feature }}</strong>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="authx-footer">
                    © {{ date('Y') }} {{ $siteName }}. {{ $settings['login_footer_text'] ?? 'Aprendizaje teórico de conducción.' }}
                </div>
            </aside>

            <section class="authx-right">
                <div class="authx-card">

                    <header class="authx-card-header">
                        <p class="authx-card-badge">{{ $settings['login_badge'] ?? 'Bienvenido' }}</p>
                        <h2>{{ $settings['login_title'] ?? 'Bienvenido a AutoEscuela Virtual' }}</h2>
                        <p>{{ $settings['login_subtitle'] ?? 'Ingresa tus credenciales para continuar con tu aprendizaje teórico de conducción.' }}</p>
                    </header>

                    @if(session('status'))
                        <div class="authx-status">{{ session('status') }}</div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="authx-form">
                        @csrf

                        <div class="authx-field">
                            <label for="email">{{ $settings['login_email_label'] ?? 'Correo electrónico' }}</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="{{ $settings['login_email_placeholder'] ?? 'tu-correo@ejemplo.com' }}" required autofocus autocomplete="email">
                            @error('email')<p class="authx-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="authx-field">
                            <label for="password">{{ $settings['login_password_label'] ?? 'Contraseña' }}</label>
                            <input id="password" name="password" type="password" placeholder="{{ $settings['login_password_placeholder'] ?? 'Ingresa tu contraseña' }}" required autocomplete="current-password">
                            @error('password')<p class="authx-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="authx-row">
                            <label class="authx-check">
                                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                                <span>{{ $settings['login_remember_label'] ?? 'Recordarme' }}</span>
                            </label>

                            @if(Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="authx-link">
                                    {{ $settings['login_forgot_text'] ?? '¿Olvidaste tu contraseña?' }}
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="authx-button">
                            {{ $settings['login_button_text'] ?? 'Iniciar sesión' }}
                        </button>
                    </form>

                    @if(Route::has('register'))
                        <p class="authx-bottom">
                            {{ $settings['login_register_text'] ?? '¿No tienes una cuenta?' }}
                            <a href="{{ route('register') }}" class="authx-link">
                                {{ $settings['login_register_link_text'] ?? 'Crear cuenta' }}
                            </a>
                        </p>
                    @endif

                </div>
            </section>

        </section>
    </main>
</body>
</html>