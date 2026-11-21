@extends('layouts.app')
@section('title','Revisión de contenido · '.config('branding.site_name'))
@section('page_title','Revisión de contenido')
@section('page_description','Control rápido de publicación y borradores')

@section('content')
<div class="cms-page">
    <header class="cms-page-header">
        <div>
            <p class="cms-eyebrow">Sprint 6</p>
            <h1>Revisión de contenido</h1>
            <span>Consulta contenido publicado y pendiente de publicación.</span>
        </div>
        <a href="{{ route($routePrefix.'.courses.index') }}" class="cms-secondary-button">Volver a cursos</a>
    </header>

    @if(session('status'))<div class="cms-alert-success"><span>✓</span>{{ session('status') }}</div>@endif

    <section class="student-home-stats">
        @foreach($summary as $item)
            <div class="student-home-stat">
                <span>{{ $item['label'] }}</span>
                <strong>{{ $item['total'] }}</strong>
                <small>{{ $item['published'] }} publicados · {{ $item['draft'] }} borradores</small>
            </div>
        @endforeach
    </section>

    <section class="cms-list-card" style="margin-top:24px;">
        <div class="cms-form-card-header">
            <div>
                <h2>Contenido pendiente de publicación</h2>
                <p>Revisa los elementos en borrador y publícalos cuando estén listos.</p>
            </div>
        </div>

        @include('content.review.partials-items',['title'=>'Cursos en borrador','items'=>$draftCourses,'type'=>'courses','routePrefix'=>$routePrefix,'empty'=>'No hay cursos en borrador.'])
        @include('content.review.partials-items',['title'=>'Módulos en borrador','items'=>$draftModules,'type'=>'modules','routePrefix'=>$routePrefix,'empty'=>'No hay módulos en borrador.'])
        @include('content.review.partials-items',['title'=>'Lecciones en borrador','items'=>$draftLessons,'type'=>'lessons','routePrefix'=>$routePrefix,'empty'=>'No hay lecciones en borrador.'])
        @include('content.review.partials-items',['title'=>'Preguntas en borrador','items'=>$draftQuestions,'type'=>'questions','routePrefix'=>$routePrefix,'empty'=>'No hay preguntas en borrador.'])
    </section>

    <section class="cms-list-card" style="margin-top:24px;">
        <div class="cms-form-card-header">
            <div>
                <h2>Contenido publicado recientemente</h2>
                <p>Elementos visibles para estudiantes si toda su jerarquía también está publicada.</p>
            </div>
        </div>

        @include('content.review.partials-items',['title'=>'Cursos publicados','items'=>$publishedCourses,'type'=>'courses','routePrefix'=>$routePrefix,'empty'=>'No hay cursos publicados.','publishedList'=>true])
        @include('content.review.partials-items',['title'=>'Módulos publicados','items'=>$publishedModules,'type'=>'modules','routePrefix'=>$routePrefix,'empty'=>'No hay módulos publicados.','publishedList'=>true])
        @include('content.review.partials-items',['title'=>'Lecciones publicadas','items'=>$publishedLessons,'type'=>'lessons','routePrefix'=>$routePrefix,'empty'=>'No hay lecciones publicadas.','publishedList'=>true])
        @include('content.review.partials-items',['title'=>'Preguntas publicadas','items'=>$publishedQuestions,'type'=>'questions','routePrefix'=>$routePrefix,'empty'=>'No hay preguntas publicadas.','publishedList'=>true])
    </section>
</div>
@endsection