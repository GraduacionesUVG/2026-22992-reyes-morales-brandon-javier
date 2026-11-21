@extends('layouts.app')
@section('title','Buscar · '.config('branding.site_name'))
@section('page_title','Buscar')
@section('page_description','Encuentra cursos, módulos y lecciones publicadas')

@section('content')
<div class="cms-page">
    <header class="cms-page-header">
        <div>
            <p class="cms-eyebrow">Búsqueda de contenido</p>
            <h1>Buscar en la plataforma</h1>
            <span>Busca contenido publicado por nombre, descripción o texto de la lección.</span>
        </div>
    </header>

    <form method="GET" action="{{ route('student.search.index') }}" class="cms-form-card" style="margin-bottom:24px;">
        <div class="cms-form-card-body">
            <div class="cms-form-grid">
                <div class="cms-field cms-field-full">
                    <label for="q">Texto de búsqueda</label>
                    <input id="q" name="q" type="search" value="{{ $query }}" placeholder="Ej. licencia, señales, cinturón, velocidad..." autofocus>
                    <small>Escribe al menos 2 caracteres para buscar.</small>
                </div>
            </div>
        </div>
        <footer class="cms-form-actions">
            <a href="{{ route('student.modules.index') }}" class="cms-cancel-button">Ver módulos</a>
            <button type="submit" class="cms-primary-button">Buscar</button>
        </footer>
    </form>

    @if(!$hasSearched)
        <div class="cms-empty">
            <h2>Realiza una búsqueda</h2>
            <p>Ingresa una palabra clave para encontrar cursos, módulos o lecciones.</p>
        </div>
    @else
        <section class="cms-list-card">
            <div class="cms-form-card-header">
                <div>
                    <h2>Resultados para “{{ $query }}”</h2>
                    <p>{{ $courses->count()+$modules->count()+$lessons->count() }} resultado{{ ($courses->count()+$modules->count()+$lessons->count())===1?'':'s' }} encontrado{{ ($courses->count()+$modules->count()+$lessons->count())===1?'':'s' }}.</p>
                </div>
            </div>

            @if($courses->isNotEmpty())
                <div style="padding:18px 22px 0;"><p class="cms-eyebrow">Cursos</p></div>
                @foreach($courses as $course)
                    <article class="course-admin-item">
                        <div class="course-admin-main">
                            <div class="course-admin-icon">C</div>
                            <div class="course-admin-info">
                                <div class="course-admin-title-row">
                                    <h2>{{ $course->title }}</h2>
                                    <span class="cms-status is-published"><span></span>Publicado</span>
                                </div>
                                <p>{{ $course->description ?: 'Sin descripción.' }}</p>
                            </div>
                        </div>
                        <div class="course-admin-actions">
                            <a href="{{ route('student.modules.index') }}" class="cms-secondary-button">Ver módulos</a>
                        </div>
                    </article>
                @endforeach
            @endif

            @if($modules->isNotEmpty())
                <div style="padding:18px 22px 0;"><p class="cms-eyebrow">Módulos</p></div>
                @foreach($modules as $module)
                    <article class="course-admin-item">
                        <div class="course-admin-main">
                            <div class="course-admin-icon">{{ str_pad($module->sort_order,2,'0',STR_PAD_LEFT) }}</div>
                            <div class="course-admin-info">
                                <div class="course-admin-title-row">
                                    <h2>{{ $module->title }}</h2>
                                    <span class="cms-status is-published"><span></span>Módulo</span>
                                </div>
                                <p>{{ $module->description ?: 'Sin descripción.' }}</p>
                                <div class="course-admin-meta"><span>{{ $module->course->title }}</span></div>
                            </div>
                        </div>
                        <div class="course-admin-actions">
                            <a href="{{ route('student.modules.show',$module) }}" class="cms-secondary-button">Abrir</a>
                        </div>
                    </article>
                @endforeach
            @endif

            @if($lessons->isNotEmpty())
                <div style="padding:18px 22px 0;"><p class="cms-eyebrow">Lecciones</p></div>
                @foreach($lessons as $lesson)
                    <article class="course-admin-item">
                        <div class="course-admin-main">
                            <div class="course-admin-icon">{{ str_pad($lesson->sort_order,2,'0',STR_PAD_LEFT) }}</div>
                            <div class="course-admin-info">
                                <div class="course-admin-title-row">
                                    <h2>{{ $lesson->title }}</h2>
                                    <span class="cms-status is-published"><span></span>Lección</span>
                                </div>
                                <p>{{ $lesson->summary ?: 'Sin resumen.' }}</p>
                                <div class="course-admin-meta">
                                    <span>{{ $lesson->module->title }}</span>
                                    <span>•</span>
                                    <span>{{ $lesson->module->course->title }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="course-admin-actions">
                            <a href="{{ route('student.lessons.show',$lesson) }}" class="cms-secondary-button">Leer</a>
                        </div>
                    </article>
                @endforeach
            @endif

            @if($courses->isEmpty() && $modules->isEmpty() && $lessons->isEmpty())
                <div class="cms-empty">
                    <h2>No se encontraron resultados</h2>
                    <p>Prueba con otra palabra clave, por ejemplo: licencia, señales, seguridad o tránsito.</p>
                </div>
            @endif
        </section>
    @endif
</div>
@endsection