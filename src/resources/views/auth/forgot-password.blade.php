<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="{{ config('branding.site_description') }}"
    >

    <title>
        Recuperar contraseña
        ·
        {{ config('branding.site_name') }}
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <style>
        :root {
            --color-primary:
                {{ config('branding.colors.primary') }};

            --color-primary-hover:
                {{ config('branding.colors.primary_hover') }};

            --color-accent:
                {{ config('branding.colors.accent') }};

            --color-background:
                {{ config('branding.colors.background') }};

            --color-surface:
                {{ config('branding.colors.surface') }};

            --color-text:
                {{ config('branding.colors.text') }};

            --color-muted:
                {{ config('branding.colors.muted') }};

            --color-border:
                {{ config('branding.colors.border') }};
        }
    </style>
    @include('partials.clarity')

</head>


<body class="login-page">

    <main class="login-shell">

        <section class="login-card">


            {{-- ========================================= --}}
            {{-- PANEL IZQUIERDO --}}
            {{-- ========================================= --}}

            <aside class="login-brand">

                <div class="login-brand-pattern"></div>


                <div class="login-brand-content">


                    <div>

                        <div class="brand-mark">
                            AV
                        </div>


                        <h1 class="brand-title">

                            {{ config(
                                'branding.site_name',
                                'AutoEscuela Virtual'
                            ) }}

                        </h1>


                        <p class="brand-description">

                            {{ config(
                                'branding.site_description',
                                'Plataforma digital para el aprendizaje de conducción vial.'
                            ) }}

                        </p>

                    </div>



                    {{-- ========================================= --}}
                    {{-- CARACTERÍSTICAS --}}
                    {{-- ========================================= --}}

                    <div class="brand-features">

                        @foreach(
                            config('branding.features', [])
                            as $feature
                        )

                            <div class="brand-feature">

                                <span
                                    class="feature-check"
                                    aria-hidden="true"
                                >

                                    <svg
                                        viewBox="0 0 20 20"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >

                                        <path
                                            d="M5 10.4L8.1 13.5L15.2 6.5"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />

                                    </svg>

                                </span>


                                <span>
                                    {{ $feature }}
                                </span>

                            </div>

                        @endforeach

                    </div>



                    {{-- ========================================= --}}
                    {{-- FOOTER IZQUIERDO --}}
                    {{-- ========================================= --}}

                    <div class="brand-footer">

                        <span>
                            © {{ date('Y') }}
                        </span>

                        <span class="brand-footer-dot">
                            •
                        </span>

                        <span>
                            {{ config(
                                'branding.site_name',
                                'AutoEscuela Virtual'
                            ) }}
                        </span>

                    </div>


                </div>

            </aside>



            {{-- ========================================= --}}
            {{-- PANEL DERECHO --}}
            {{-- ========================================= --}}

            <section class="login-form-panel">

                <div class="login-form-container">


                    {{-- ========================================= --}}
                    {{-- ENCABEZADO --}}
                    {{-- ========================================= --}}

                    <header class="login-header">

                        <p class="login-eyebrow">
                            Recuperación
                        </p>


                        <h2 class="login-title">
                            Recupera tu contraseña
                        </h2>


                        <p class="login-description">

                            Ingresa el correo electrónico
                            asociado a tu cuenta y te
                            enviaremos un enlace para crear
                            una nueva contraseña.

                        </p>

                    </header>



                    {{-- ========================================= --}}
                    {{-- MENSAJE DE ÉXITO --}}
                    {{-- ========================================= --}}

                    @if(session('status'))

                        <div
                            style="
                                margin-bottom: 20px;
                                padding: 14px 16px;
                                border-radius: 10px;
                                border: 1px solid #bbf7d0;
                                background: #f0fdf4;
                                color: #15803d;
                                font-size: 14px;
                                line-height: 1.5;
                            "
                        >

                            {{ session('status') }}

                        </div>

                    @endif



                    {{-- ========================================= --}}
                    {{-- FORMULARIO --}}
                    {{-- ========================================= --}}

                    <form
                        method="POST"
                        action="{{ route('password.email') }}"
                        class="login-form"
                    >

                        @csrf


                        <div class="form-group">

                            <label
                                for="email"
                                class="form-label"
                            >
                                Correo electrónico
                            </label>


                            <div class="input-wrapper">


                                {{-- ICONO EMAIL --}}

                                <span
                                    class="input-icon"
                                    aria-hidden="true"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >

                                        <path
                                            d="M4 6.5L12 12.5L20 6.5"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />

                                        <rect
                                            x="3.5"
                                            y="5"
                                            width="17"
                                            height="14"
                                            rx="3"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                    </svg>

                                </span>


                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    placeholder="nombre@correo.com"
                                    required
                                    autofocus
                                    class="form-input"
                                >

                            </div>


                            {{-- ========================================= --}}
                            {{-- ERROR --}}
                            {{-- ========================================= --}}

                            @error('email')

                                <p
                                    class="form-error"
                                    role="alert"
                                >
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>



                        {{-- ========================================= --}}
                        {{-- BOTÓN --}}
                        {{-- ========================================= --}}

                        <button
                            type="submit"
                            class="login-button"
                        >

                            <span>
                                Enviar enlace de recuperación
                            </span>


                            <svg
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                                aria-hidden="true"
                            >

                                <path
                                    d="M4 10H16M11 5L16 10L11 15"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />

                            </svg>

                        </button>


                    </form>



                    {{-- ========================================= --}}
                    {{-- VOLVER AL LOGIN --}}
                    {{-- ========================================= --}}

                    <div class="register-section">

                        <p>
                            ¿Recordaste tu contraseña?
                        </p>


                        <a href="{{ route('login') }}">
                            Volver al inicio de sesión
                        </a>

                    </div>



                    {{-- ========================================= --}}
                    {{-- SEPARADOR --}}
                    {{-- ========================================= --}}

                    <div class="login-divider">

                        <span></span>

                        <p>
                            acceso seguro
                        </p>

                        <span></span>

                    </div>



                    {{-- ========================================= --}}
                    {{-- FOOTER --}}
                    {{-- ========================================= --}}

                    <footer class="login-legal">

                        <a href="#">
                            Términos
                        </a>

                        <span>
                            •
                        </span>

                        <a href="#">
                            Privacidad
                        </a>

                    </footer>


                </div>

            </section>

        </section>

    </main>

</body>

</html>