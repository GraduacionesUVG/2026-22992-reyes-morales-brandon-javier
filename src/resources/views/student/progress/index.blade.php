@extends('layouts.app')

@section('title','Mi progreso · '.config('branding.site_name'))
@section('page_title','Mi progreso')
@section('page_description','Seguimiento de tus lecciones completadas')

@section('content')

<div class="modules-page">

    <header class="modules-heading">

        <p>Seguimiento</p>

        <h1>
            Mi progreso
        </h1>

        <span>
            Consulta cuánto has avanzado en el contenido publicado.
        </span>

    </header>


    <div
        class="student-progress-card"
        style="margin-bottom:22px"
    >

        <div class="student-progress-top">

            <div>

                <span class="student-card-label">
                    Progreso general
                </span>

                <strong>
                    {{ $progress }}%
                </strong>

            </div>

        </div>


        <div class="student-progress-track">

            <span
                style="width:{{ $progress }}%"
            ></span>

        </div>


        <div class="student-progress-meta">

            <span>
                {{ $completedLessons }}
                de
                {{ $totalLessons }}
                lecciones completadas
            </span>

            <span>
                {{ $totalLessons-$completedLessons }}
                pendientes
            </span>

        </div>

    </div>


    @forelse($courses as $course)

        <section class="course-block">

            <div class="course-heading">

                <div>

                    <span>
                        Curso
                    </span>

                    <h2>
                        {{ $course->title }}
                    </h2>

                    <p>
                        {{ $course->completed_lessons_count }}
                        de
                        {{ $course->total_lessons_count }}
                        lecciones completadas
                        ·
                        {{ $course->progress_percent }}%
                    </p>

                </div>


                <div class="course-count">

                    {{ $course->progress_percent }}

                    <span>
                        por ciento
                    </span>

                </div>

            </div>


            <div class="module-list">

                @forelse($course->publishedModules as $module)

                    <a
                        href="{{ route('student.modules.show',$module) }}"
                        class="module-card"
                    >

                        <div class="module-number">
                            {{ str_pad($module->sort_order,2,'0',STR_PAD_LEFT) }}
                        </div>


                        <div class="module-info">

                            <span>
                                Módulo {{ $module->sort_order }}
                            </span>

                            <h3>
                                {{ $module->title }}
                            </h3>


                            <div class="module-meta">

                                <span>
                                    {{ $module->completed_lessons_count }}/{{ $module->publishedLessons->count() }}
                                    completadas
                                    ·
                                    {{ $module->progress_percent }}%
                                </span>

                            </div>

                        </div>


                        <svg
                            class="module-arrow"
                            viewBox="0 0 20 20"
                            fill="none"
                        >

                            <path
                                d="M7 4L13 10L7 16"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                        </svg>

                    </a>


                @empty

                    <div class="cms-module-empty">
                        Este curso no tiene módulos publicados.
                    </div>

                @endforelse

            </div>

        </section>


    @empty

        <div class="modules-empty">

            <h2>
                Aún no hay contenido publicado
            </h2>

            <p>
                Tu progreso aparecerá cuando existan cursos y lecciones disponibles.
            </p>

        </div>

    @endforelse

</div>

@endsection