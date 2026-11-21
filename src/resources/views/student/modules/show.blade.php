@extends('layouts.app')

@section('title',$module->title.' · '.config('branding.site_name'))
@section('page_title','Módulo')
@section('page_description',$module->course->title)

@section('content')

<div class="module-detail">

    <a
        href="{{ route('student.modules.index') }}"
        class="module-back"
    >

        <svg viewBox="0 0 20 20" fill="none">
            <path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>

        Volver a módulos

    </a>


    <header class="module-detail-heading">

        <span>
            Módulo {{ $module->sort_order }}
        </span>

        <h1>
            {{ $module->title }}
        </h1>

        @if($module->description)

            <p>
                {{ $module->description }}
            </p>

        @endif

    </header>


    <div
        class="student-progress-card"
        style="margin-bottom:18px"
    >

        <div class="student-progress-top">

            <div>

                <span class="student-card-label">
                    Progreso del módulo
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


    <section class="lesson-section">

        <div class="lesson-section-title">

            <div>
                <span>Contenido</span>
                <h2>Lecciones</h2>
            </div>

            <strong>
                {{ $module->publishedLessons->count() }}
            </strong>

        </div>


        <div class="lesson-list">

            @forelse($module->publishedLessons as $lesson)

                @php
                    $completed=$completedLessonIds->contains($lesson->id);
                @endphp

                <a
                    href="{{ route('student.lessons.show',$lesson) }}"
                    class="lesson-row"
                >

                    <div class="lesson-order">
                        {{ str_pad($lesson->sort_order,2,'0',STR_PAD_LEFT) }}
                    </div>


                    <div class="lesson-info">

                        <h3>
                            {{ $lesson->title }}
                        </h3>


                        @if($lesson->summary)

                            <p>
                                {{ $lesson->summary }}
                            </p>

                        @endif


                        @if($lesson->estimated_minutes)

                            <span>
                                {{ $lesson->estimated_minutes }} min
                            </span>

                        @endif

                    </div>


                    <span class="lesson-status">

                        {{ $completed
                            ? 'Completada'
                            : 'Disponible' }}

                    </span>


                    <svg
                        class="lesson-arrow"
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

                <div class="lesson-empty">

                    <h3>
                        No hay lecciones publicadas
                    </h3>

                    <p>
                        El contenido aparecerá aquí cuando esté disponible.
                    </p>

                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection