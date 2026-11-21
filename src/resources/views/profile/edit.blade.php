@extends('layouts.app')


@section(
    'title',
    'Mi perfil · AutoEscuela Virtual'
)
@section('page_title', 'Mi perfil')
@section('page_description', 'Información personal y seguridad de tu cuenta')

@section('content')


<style>

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    |
    | Estética limpia inspirada en principios de diseño de Apple:
    |
    | - mucho espacio
    | - fondos neutros
    | - jerarquía clara
    | - bordes discretos
    | - pocas acciones principales
    |
    */

    .profile-page {
        width: 100%;
        max-width: 1050px;
        margin: 0 auto;
        padding: 22px 12px 60px;
        color: #1d1d1f;
    }


    /*
    |--------------------------------------------------------------------------
    | CABECERA
    |--------------------------------------------------------------------------
    */

    .profile-heading {
        margin-bottom: 32px;
    }


    .profile-eyebrow {
        margin: 0 0 7px;
        color: #0071e3;
        font-size: 13px;
        font-weight: 600;
    }


    .profile-heading h1 {
        margin: 0;
        color: #1d1d1f;
        font-size: clamp(
            30px,
            4vw,
            42px
        );
        font-weight: 700;
        letter-spacing: -1.3px;
        line-height: 1.08;
    }


    .profile-heading-description {
        max-width: 630px;
        margin: 10px 0 0;
        color: #6e6e73;
        font-size: 15px;
        line-height: 1.6;
    }


    /*
    |--------------------------------------------------------------------------
    | RESUMEN DEL USUARIO
    |--------------------------------------------------------------------------
    */

    .profile-summary {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 26px;
        padding: 22px 24px;
        border: 1px solid #e5e5e7;
        border-radius: 22px;
        background: rgba(
            255,
            255,
            255,
            0.92
        );
    }


    .profile-avatar {
        display: flex;
        width: 66px;
        height: 66px;
        flex: 0 0 66px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background:
            linear-gradient(
                145deg,
                #147ce5,
                #0057b8
            );
        color: #ffffff;
        font-size: 20px;
        font-weight: 700;
        letter-spacing: -0.4px;
        box-shadow:
            0 7px 18px
            rgba(0, 113, 227, 0.18);
    }


    .profile-summary-info {
        min-width: 0;
        flex: 1;
    }


    .profile-summary-name {
        margin: 0;
        color: #1d1d1f;
        font-size: 20px;
        font-weight: 650;
        letter-spacing: -0.35px;
    }


    .profile-summary-email {
        margin: 4px 0 0;
        color: #6e6e73;
        font-size: 14px;
        overflow-wrap: anywhere;
    }


    .profile-role {
        display: inline-flex;
        align-items: center;
        margin-top: 10px;
        padding: 6px 10px;
        border-radius: 100px;
        background: #f5f5f7;
        color: #515154;
        font-size: 12px;
        font-weight: 600;
    }


    /*
    |--------------------------------------------------------------------------
    | TARJETAS
    |--------------------------------------------------------------------------
    */

    .profile-grid {
        display: grid;
        gap: 20px;
    }


    .profile-card {
        overflow: hidden;
        border: 1px solid #e5e5e7;
        border-radius: 22px;
        background: #ffffff;
        box-shadow:
            0 1px 2px
            rgba(0, 0, 0, 0.02);
    }


    .profile-card-header {
        padding: 24px 26px 20px;
        border-bottom: 1px solid #eeeeef;
    }


    .profile-card-title-row {
        display: flex;
        align-items: center;
        gap: 11px;
    }


    .profile-card-icon {
        display: flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #f5f5f7;
        color: #1d1d1f;
    }


    .profile-card-icon svg {
        width: 18px;
        height: 18px;
    }


    .profile-card-title {
        margin: 0;
        color: #1d1d1f;
        font-size: 17px;
        font-weight: 650;
        letter-spacing: -0.2px;
    }


    .profile-card-description {
        margin: 8px 0 0 45px;
        color: #86868b;
        font-size: 13px;
        line-height: 1.55;
    }


    .profile-card-body {
        padding: 25px 26px 27px;
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIOS
    |--------------------------------------------------------------------------
    */

    .profile-fields {
        display: grid;
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
        gap: 20px;
    }


    .profile-field {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }


    .profile-field-full {
        grid-column: 1 / -1;
    }


    .profile-label {
        color: #3a3a3c;
        font-size: 13px;
        font-weight: 600;
    }


    .profile-input {
        width: 100%;
        min-height: 48px;
        box-sizing: border-box;
        padding: 0 14px;
        border: 1px solid #d2d2d7;
        border-radius: 12px;
        outline: none;
        background: #ffffff;
        color: #1d1d1f;
        font-family: inherit;
        font-size: 15px;
        transition:
            border-color 160ms ease,
            box-shadow 160ms ease;
    }


    .profile-input:hover {
        border-color: #b7b7bc;
    }


    .profile-input:focus {
        border-color: #0071e3;
        box-shadow:
            0 0 0 3px
            rgba(0, 113, 227, 0.12);
    }


    .profile-readonly {
        display: flex;
        min-height: 48px;
        align-items: center;
        padding: 0 14px;
        border: 1px solid #ededee;
        border-radius: 12px;
        background: #f7f7f8;
        color: #6e6e73;
        font-size: 14px;
    }


    .profile-help {
        margin: -1px 0 0;
        color: #86868b;
        font-size: 12px;
        line-height: 1.45;
    }


    .profile-error {
        margin: -1px 0 0;
        color: #d70015;
        font-size: 12px;
        line-height: 1.45;
    }


    /*
    |--------------------------------------------------------------------------
    | ACCIONES
    |--------------------------------------------------------------------------
    */

    .profile-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-top: 24px;
        padding-top: 22px;
        border-top: 1px solid #eeeeef;
    }


    .profile-button {
        display: inline-flex;
        min-height: 42px;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 0 18px;
        border: 0;
        border-radius: 980px;
        background: #0071e3;
        color: #ffffff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition:
            background 160ms ease,
            transform 100ms ease;
    }


    .profile-button:hover {
        background: #0077ed;
    }


    .profile-button:active {
        transform: scale(0.985);
    }


    /*
    |--------------------------------------------------------------------------
    | MENSAJES
    |--------------------------------------------------------------------------
    */

    .profile-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 20px;
        padding: 13px 15px;
        border-radius: 13px;
        font-size: 13px;
        line-height: 1.5;
    }


    .profile-alert-success {
        border: 1px solid #b9e8c7;
        background: #f2fbf4;
        color: #237a3b;
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMACIÓN DE SEGURIDAD
    |--------------------------------------------------------------------------
    */

    .security-note {
        display: flex;
        gap: 12px;
        margin-top: 21px;
        padding: 14px 16px;
        border-radius: 13px;
        background: #f5f5f7;
        color: #6e6e73;
        font-size: 12px;
        line-height: 1.55;
    }


    .security-note svg {
        width: 17px;
        min-width: 17px;
        margin-top: 1px;
    }


    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media (
        max-width: 700px
    ) {

        .profile-page {
            padding:
                16px 4px
                40px;
        }


        .profile-summary {
            align-items: flex-start;
            padding: 20px;
            border-radius: 18px;
        }


        .profile-avatar {
            width: 56px;
            height: 56px;
            flex-basis: 56px;
            font-size: 17px;
        }


        .profile-fields {
            grid-template-columns: 1fr;
        }


        .profile-field-full {
            grid-column: auto;
        }


        .profile-card {
            border-radius: 18px;
        }


        .profile-card-header,
        .profile-card-body {
            padding-left: 20px;
            padding-right: 20px;
        }


        .profile-card-description {
            margin-left: 0;
        }


        .profile-actions {
            align-items: stretch;
        }


        .profile-button {
            width: 100%;
        }

    }

</style>


@php

    /*
    |--------------------------------------------------------------------------
    | Iniciales del usuario
    |--------------------------------------------------------------------------
    */

    $words = preg_split(
        '/\s+/',
        trim($user->name)
    );


    $initials = collect($words)

        ->filter()

        ->take(2)

        ->map(
            fn ($word) =>
                mb_strtoupper(
                    mb_substr($word, 0, 1)
                )
        )

        ->implode('');


    /*
    |--------------------------------------------------------------------------
    | Nombre amigable del rol
    |--------------------------------------------------------------------------
    */

    $roleLabel = match ($user->role) {

        'admin' =>
            'Administrador',

        'gestor' =>
            'Gestor de contenido',

        'estudiante' =>
            'Estudiante',

        default =>
            ucfirst($user->role),

    };

@endphp



<div class="profile-page">


    {{-- ====================================================== --}}
    {{-- ENCABEZADO --}}
    {{-- ====================================================== --}}

    <header class="profile-heading">

        <p class="profile-eyebrow">
            Cuenta
        </p>


        <h1>
            Mi perfil
        </h1>


        <p class="profile-heading-description">

            Administra tu información personal y mantén
            segura tu cuenta.

        </p>

    </header>



    {{-- ====================================================== --}}
    {{-- RESUMEN --}}
    {{-- ====================================================== --}}

    <section class="profile-summary">


        <div class="profile-avatar">

            {{ $initials ?: 'AV' }}

        </div>


        <div class="profile-summary-info">

            <h2 class="profile-summary-name">

                {{ $user->name }}

            </h2>


            <p class="profile-summary-email">

                {{ $user->email }}

            </p>


            <span class="profile-role">

                {{ $roleLabel }}

            </span>

        </div>


    </section>



    <div class="profile-grid">


        {{-- ================================================== --}}
        {{-- INFORMACIÓN PERSONAL --}}
        {{-- ================================================== --}}

        <section class="profile-card">


            <header class="profile-card-header">


                <div class="profile-card-title-row">


                    <div
                        class="profile-card-icon"
                        aria-hidden="true"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >

                            <circle
                                cx="12"
                                cy="8"
                                r="3.5"
                                stroke="currentColor"
                                stroke-width="1.7"
                            />

                            <path
                                d="M5.5 20C5.5 16.4 8.4 13.5 12 13.5C15.6 13.5 18.5 16.4 18.5 20"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />

                        </svg>

                    </div>


                    <h2 class="profile-card-title">

                        Información personal

                    </h2>


                </div>


                <p class="profile-card-description">

                    Estos datos identifican tu cuenta dentro
                    de AutoEscuela Virtual.

                </p>


            </header>



            <div class="profile-card-body">


                @if(session('profile_status'))

                    <div
                        class="
                            profile-alert
                            profile-alert-success
                        "
                        role="status"
                    >

                        <span>
                            ✓
                        </span>

                        <span>

                            {{ session('profile_status') }}

                        </span>

                    </div>

                @endif



                <form
                    method="POST"
                    action="{{ route('profile.update') }}"
                >

                    @csrf
                    @method('PATCH')


                    <div class="profile-fields">


                        {{-- ====================================== --}}
                        {{-- NOMBRE --}}
                        {{-- ====================================== --}}

                        <div class="profile-field">

                            <label
                                for="name"
                                class="profile-label"
                            >
                                Nombre
                            </label>


                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name', $user->name) }}"
                                autocomplete="name"
                                required
                                class="profile-input"
                            >


                            @error('name')

                                <p class="profile-error">

                                    {{ $message }}

                                </p>

                            @enderror

                        </div>



                        {{-- ====================================== --}}
                        {{-- CORREO --}}
                        {{-- ====================================== --}}

                        <div class="profile-field">

                            <label
                                for="email"
                                class="profile-label"
                            >
                                Correo electrónico
                            </label>


                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email', $user->email) }}"
                                autocomplete="email"
                                required
                                class="profile-input"
                            >


                            @error('email')

                                <p class="profile-error">

                                    {{ $message }}

                                </p>

                            @enderror

                        </div>



                        {{-- ====================================== --}}
                        {{-- ROL --}}
                        {{-- ====================================== --}}

                        <div
                            class="
                                profile-field
                                profile-field-full
                            "
                        >

                            <span class="profile-label">

                                Rol de la plataforma

                            </span>


                            <div class="profile-readonly">

                                {{ $roleLabel }}

                            </div>


                            <p class="profile-help">

                                El rol es administrado por la
                                plataforma y no puede cambiarse
                                desde el perfil.

                            </p>

                        </div>


                    </div>



                    <div class="profile-actions">

                        <button
                            type="submit"
                            class="profile-button"
                        >

                            Guardar cambios

                        </button>

                    </div>


                </form>


            </div>


        </section>



        {{-- ================================================== --}}
        {{-- SEGURIDAD --}}
        {{-- ================================================== --}}

        <section class="profile-card">


            <header class="profile-card-header">


                <div class="profile-card-title-row">


                    <div
                        class="profile-card-icon"
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

                    </div>


                    <h2 class="profile-card-title">

                        Seguridad

                    </h2>


                </div>


                <p class="profile-card-description">

                    Cambia tu contraseña utilizando primero
                    tu contraseña actual.

                </p>


            </header>



            <div class="profile-card-body">


                @if(session('password_status'))

                    <div
                        class="
                            profile-alert
                            profile-alert-success
                        "
                        role="status"
                    >

                        <span>
                            ✓
                        </span>

                        <span>

                            {{ session('password_status') }}

                        </span>

                    </div>

                @endif



                <form
                    method="POST"
                    action="{{
                        route(
                            'profile.password.update'
                        )
                    }}"
                >

                    @csrf
                    @method('PUT')


                    <div class="profile-fields">


                        {{-- ====================================== --}}
                        {{-- CONTRASEÑA ACTUAL --}}
                        {{-- ====================================== --}}

                        <div
                            class="
                                profile-field
                                profile-field-full
                            "
                        >

                            <label
                                for="current_password"
                                class="profile-label"
                            >

                                Contraseña actual

                            </label>


                            <input
                                id="current_password"
                                name="current_password"
                                type="password"
                                autocomplete="current-password"
                                placeholder="Ingresa tu contraseña actual"
                                required
                                class="profile-input"
                            >


                            @error(
                                'current_password',
                                'updatePassword'
                            )

                                <p class="profile-error">

                                    La contraseña actual no es correcta.

                                </p>

                            @enderror

                        </div>



                        {{-- ====================================== --}}
                        {{-- NUEVA CONTRASEÑA --}}
                        {{-- ====================================== --}}

                        <div class="profile-field">

                            <label
                                for="new_password"
                                class="profile-label"
                            >

                                Nueva contraseña

                            </label>


                            <input
                                id="new_password"
                                name="password"
                                type="password"
                                autocomplete="new-password"
                                placeholder="Mínimo 8 caracteres"
                                required
                                class="profile-input"
                            >


                            @error(
                                'password',
                                'updatePassword'
                            )

                                <p class="profile-error">

                                    {{ $message }}

                                </p>

                            @enderror

                        </div>



                        {{-- ====================================== --}}
                        {{-- CONFIRMAR --}}
                        {{-- ====================================== --}}

                        <div class="profile-field">

                            <label
                                for="password_confirmation"
                                class="profile-label"
                            >

                                Confirmar contraseña

                            </label>


                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                autocomplete="new-password"
                                placeholder="Repite la contraseña"
                                required
                                class="profile-input"
                            >

                        </div>


                    </div>



                    <div class="security-note">


                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true"
                        >

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                                stroke="currentColor"
                                stroke-width="1.7"
                            />

                            <path
                                d="M12 10V16"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />

                            <circle
                                cx="12"
                                cy="7"
                                r="1"
                                fill="currentColor"
                            />

                        </svg>


                        <span>

                            Utiliza una contraseña diferente
                            a las que empleas en otros servicios.
                            Se requieren al menos ocho caracteres,
                            incluyendo letras y números.

                        </span>


                    </div>



                    <div class="profile-actions">

                        <button
                            type="submit"
                            class="profile-button"
                        >

                            Cambiar contraseña

                        </button>

                    </div>


                </form>


            </div>


        </section>


    </div>


</div>


@endsection