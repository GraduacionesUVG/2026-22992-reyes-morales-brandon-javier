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
        Crear cuenta
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


            {{-- ============================================= --}}
            {{-- PANEL IZQUIERDO --}}
            {{-- ============================================= --}}

            <aside class="login-brand">


                <div class="login-brand-pattern"></div>


                <div class="login-brand-content">


                    <div>


                        <div class="brand-mark">
                            AV
                        </div>


                        <h1 class="brand-title">

                            {{ config('branding.site_name') }}

                        </h1>


                        <p class="brand-description">

                            {{ config('branding.site_description') }}

                        </p>


                    </div>



                    <div class="brand-features">


                        @foreach(
                            config('branding.features')
                            as $feature
                        )


                            <div class="brand-feature">


                                <span
                                    class="feature-check"
                                >

                                    ✓

                                </span>


                                <span>

                                    {{ $feature }}

                                </span>


                            </div>


                        @endforeach


                    </div>



                    <div class="brand-footer">

                        <span>
                            © {{ date('Y') }}
                        </span>

                        <span>
                            •
                        </span>

                        <span>
                            {{ config('branding.site_name') }}
                        </span>

                    </div>


                </div>


            </aside>



            {{-- ============================================= --}}
            {{-- REGISTRO --}}
            {{-- ============================================= --}}

            <section class="login-form-panel">


                <div class="login-form-container">


                    <header class="login-header">


                        <p class="login-eyebrow">

                            Nuevo estudiante

                        </p>


                        <h2 class="login-title">

                            Crear una cuenta

                        </h2>


                        <p class="login-description">

                            Regístrate para comenzar tu
                            aprendizaje de conducción vial.

                        </p>


                    </header>



                    <form
                        method="POST"
                        action="{{ route('register.submit') }}"
                        class="login-form"
                    >

                        @csrf



                        {{-- ============================================= --}}
                        {{-- NOMBRE --}}
                        {{-- ============================================= --}}

                        <div class="form-group">


                            <label
                                for="name"
                                class="form-label"
                            >

                                Nombre completo

                            </label>


                            <div class="input-wrapper">


                                <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="{{ old('name') }}"
                                    autocomplete="name"
                                    placeholder="Nombre completo"
                                    required
                                    autofocus
                                    class="form-input"
                                >


                            </div>


                            @error('name')

                                <p
                                    class="form-error"
                                    role="alert"
                                >

                                    {{ $message }}

                                </p>

                            @enderror


                        </div>



                        {{-- ============================================= --}}
                        {{-- EMAIL --}}
                        {{-- ============================================= --}}

                        <div class="form-group">


                            <label
                                for="email"
                                class="form-label"
                            >

                                Correo electrónico

                            </label>


                            <div class="input-wrapper">


                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    placeholder="nombre@correo.com"
                                    required
                                    class="form-input"
                                >


                            </div>


                            @error('email')

                                <p
                                    class="form-error"
                                    role="alert"
                                >

                                    {{ $message }}

                                </p>

                            @enderror


                        </div>



                        {{-- ============================================= --}}
                        {{-- CONTRASEÑA --}}
                        {{-- ============================================= --}}

                        <div class="form-group">


                            <label
                                for="password"
                                class="form-label"
                            >

                                Contraseña

                            </label>


                            <div class="input-wrapper">


                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="new-password"
                                    placeholder="Mínimo 8 caracteres"
                                    required
                                    class="form-input"
                                >


                            </div>


                            @error('password')

                                <p
                                    class="form-error"
                                    role="alert"
                                >

                                    {{ $message }}

                                </p>

                            @enderror


                        </div>



                        {{-- ============================================= --}}
                        {{-- CONFIRMAR CONTRASEÑA --}}
                        {{-- ============================================= --}}

                        <div class="form-group">


                            <label
                                for="password_confirmation"
                                class="form-label"
                            >

                                Confirmar contraseña

                            </label>


                            <div class="input-wrapper">


                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    placeholder="Repite tu contraseña"
                                    required
                                    class="form-input"
                                >


                            </div>


                        </div>



                        {{-- ============================================= --}}
                        {{-- BOTÓN --}}
                        {{-- ============================================= --}}

                        <button
                            type="submit"
                            class="login-button"
                        >

                            Crear mi cuenta

                        </button>


                    </form>



                    {{-- ============================================= --}}
                    {{-- LOGIN --}}
                    {{-- ============================================= --}}

                    <div class="register-section">


                        <p>

                            ¿Ya tienes una cuenta?

                        </p>


                        <a href="{{ route('login') }}">

                            Iniciar sesión

                        </a>


                    </div>


                </div>


            </section>


        </section>


    </main>


</body>

</html>