@extends('layouts.app')


@section(
    'title',
    'Dashboard · AutoEscuela Virtual'
)

@section('page_title', 'Inicio')
@section('page_description', 'Resumen de tu actividad en la plataforma')

@section('content')


    {{-- ============================================= --}}
    {{-- ENCABEZADO --}}
    {{-- ============================================= --}}

    <div>

        <p
            class="
                text-sm
                font-medium
                text-blue-600
            "
        >

            Panel principal

        </p>


        <h1
            class="
                mt-1
                text-3xl
                font-bold
                tracking-tight
                text-slate-900
            "
        >

            Bienvenido,
            {{ auth()->user()->name }}

        </h1>


        <p
            class="
                mt-2
                text-slate-500
            "
        >

            @if(auth()->user()->isAdmin())

                Administración general de la plataforma educativa.

            @elseif(auth()->user()->isGestor())

                Gestiona el contenido educativo de la plataforma.

            @else

                Continúa con tu proceso de aprendizaje.

            @endif

        </p>

    </div>



    {{-- ============================================= --}}
    {{-- ADMINISTRADOR --}}
    {{-- ============================================= --}}

    @if(auth()->user()->isAdmin())

        <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Usuarios</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $usersCount ?? 0 }}</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Cursos</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $coursesCount ?? 0 }}</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Módulos</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $modulesCount ?? 0 }}</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Lecciones</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $lessonsCount ?? 0 }}</p>
            </div>

        </div>



    {{-- ============================================= --}}
    {{-- GESTOR --}}
    {{-- ============================================= --}}

    @elseif(auth()->user()->isGestor())

        <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Cursos</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $coursesCount ?? 0 }}</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Módulos</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $modulesCount ?? 0 }}</p>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-sm text-slate-500">Lecciones</p>
                <p class="mt-2 text-3xl font-bold text-slate-900">{{ $lessonsCount ?? 0 }}</p>
            </div>

        </div>

    {{-- ============================================= --}}
    {{-- ESTUDIANTE --}}
    {{-- ============================================= --}}

    @else


        <div
            class="
                mt-8
                grid
                grid-cols-1
                gap-6
                md:grid-cols-3
            "
        >


            <div
                class="
                    rounded-2xl
                    bg-white
                    p-6
                    shadow-sm
                "
            >

                <p class="text-sm text-slate-500">
                    Cursos disponibles
                </p>

                <p
                    class="
                        mt-2
                        text-3xl
                        font-bold
                        text-slate-900
                    "
                >
                    0
                </p>

            </div>


            <div
                class="
                    rounded-2xl
                    bg-white
                    p-6
                    shadow-sm
                "
            >

                <p class="text-sm text-slate-500">
                    Progreso general
                </p>

                <p
                    class="
                        mt-2
                        text-3xl
                        font-bold
                        text-slate-900
                    "
                >
                    0 %
                </p>

            </div>


            <div
                class="
                    rounded-2xl
                    bg-white
                    p-6
                    shadow-sm
                "
            >

                <p class="text-sm text-slate-500">
                    Evaluaciones
                </p>

                <p
                    class="
                        mt-2
                        text-3xl
                        font-bold
                        text-slate-900
                    "
                >
                    0
                </p>

            </div>


        </div>


    @endif


@endsection