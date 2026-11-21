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
        Nueva contraseña
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



                    <div class="brand-footer">

                        <span>
                            © {{ date('Y') }}
                        </span>

                        <span class="brand-footer-dot">
                            •
                        </span>

                        <span>
                            {{ config('branding.site_name') }}
                        </span>

                    </div>


                </div>


            </aside>



            {{-- ============================================= --}}
            {{-- NUEVA CONTRASEÑA --}}
            {{-- ============================================= --}}

            <section class="login-form-panel">


                <div class="login-form-container">


                    <header class="login-header">


                        <p class="login-eyebrow">

                            Seguridad

                        </p>


                        <h2 class="login-title">

                            Crea una nueva contraseña

                        </h2>


                        <p class="login-description">

                            Introduce tu nueva contraseña para
                            recuperar el acceso a tu cuenta.

                        </p>


                    </header>



                    <form
                        method="POST"
                        action="{{ route('password.update') }}"
                        class="login-form"
                    >

                        @csrf



                        {{-- ============================================= --}}
                        {{-- TOKEN --}}
                        {{-- ============================================= --}}

                        <input
                            type="hidden"
                            name="token"
                            value="{{ $token }}"
                        >



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
                                    value="{{ old('email', $email) }}"
                                    autocomplete="email"
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
                        {{-- NUEVA CONTRASEÑA --}}
                        {{-- ============================================= --}}

                        <div class="form-group">


                            <label
                                for="password"
                                class="form-label"
                            >

                                Nueva contraseña

                            </label>


                            <div class="input-wrapper">


                                <span
                                    class="input-icon"
                                    aria-hidden="true"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >

                                        <rect
                                            x="5"
                                            y="10"
                                            width="14"
                                            height="10"
                                            rx="3"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                        <path
                                            d="M8 10V7.5C8 5.29 9.79 3.5 12 3.5C14.21 3.5 16 5.29 16 7.5V10"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                            stroke-linecap="round"
                                        />

                                    </svg>


                                </span>


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

                                Confirmar nueva contraseña

                            </label>


                            <div class="input-wrapper">


                                <span
                                    class="input-icon"
                                    aria-hidden="true"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >

                                        <rect
                                            x="5"
                                            y="10"
                                            width="14"
                                            height="10"
                                            rx="3"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />

                                        <path
                                            d="M8 10V7.5C8 5.29 9.79 3.5 12 3.5C14.21 3.5 16 5.29 16 7.5V10"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                            stroke-linecap="round"
                                        />

                                    </svg>


                                </span>


                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    placeholder="Repite la nueva contraseña"
                                    required
                                    class="form-input"
                                >


                            </div>


                        </div>



                        <button
                            type="submit"
                            class="login-button"
                        >

                            <span>
                                Actualizar contraseña
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



                    <div class="register-section">


                        <a href="{{ route('login') }}">

                            Volver al inicio de sesión

                        </a>


                    </div>


                </div>


            </section>


        </section>


    </main>


</body>

</html>