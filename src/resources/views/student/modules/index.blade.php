@extends('layouts.app')

@section('title','Módulos · '.config('branding.site_name'))
@section('page_title','Módulos')
@section('page_description','Contenido disponible para tu aprendizaje')

@section('content')

<div class="modules-page">

    <header class="modules-heading">

        <p>Aprendizaje</p>

        <h1>
            Módulos de estudio
        </h1>

        <span>
            Avanza de forma ordenada a través del contenido disponible.
        </span>

    </header>


    @forelse($courses as $course)

        <section class="course-block">

            <div class="course-heading">

                <div>

                    <span>Curso</span>

                    <h2>
                        {{ $course->title }}
                    </h2>

                    @if($course->description)

                        <p>
                            {{ $course->description }}
                        </p>

                    @endif

                </div>


                <div class="course-count">

                    {{ $course->publishedModules->count() }}

                    <span>
                        {{ $course->publishedModules->count()===1
                            ? 'módulo'
                            : 'módulos' }}
                    </span>

                </div>

            </div>


            <div class="module-list">

                @foreach($course->publishedModules as $module)

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

                            @if($module->description)

                                <p>
                                    {{ $module->description }}
                                </p>

                            @endif


                            <div class="module-meta">

                                <span>
                                    {{ $module->completed_lessons_count }}/{{ $module->published_lessons_count }}
                                    lecciones completadas
                                </span>

                                <span>
                                    · {{ $module->progress_percent }}%
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

                @endforeach

            </div>

        </section>


    @empty

        <div class="modules-empty">

            <h2>
                No hay módulos disponibles
            </h2>

            <p>
                El contenido aparecerá aquí cuando sea publicado.
            </p>

        </div>

    @endforelse

</div>

@endsection