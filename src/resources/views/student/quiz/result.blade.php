@extends('layouts.app')
@section('title','Resultado · '.config('branding.site_name'))
@section('page_title','Resultado del simulador')
@section('page_description',$attempt->module->title)

@section('content')
<div class="cms-page">
    <a href="{{ route('student.quiz.index') }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver al simulador
    </a>

    <header class="cms-page-header">
        <div><p class="cms-eyebrow">{{ $attempt->module->course->title }}</p><h1>{{ $attempt->module->title }}</h1><span>Resultado obtenido en el intento.</span></div>
        <a href="{{ route('student.quiz.show',$attempt->module) }}" class="cms-secondary-button">Repetir simulador</a>
    </header>

    <section class="student-home-stats">
        <div class="student-home-stat"><span>Resultado</span><strong>{{ number_format((float)$attempt->score,2) }}%</strong></div>
        <div class="student-home-stat"><span>Correctas</span><strong>{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</strong></div>
        <div class="student-home-stat"><span>Estado</span><strong>{{ $attempt->resultLabel() }}</strong></div>
    </section>

    <section class="cms-list-card" style="margin-top:24px;">
        @foreach($attempt->answers as $answer)
            @php $correctOption=$answer->question->options->firstWhere('is_correct',true); @endphp
            <article class="course-admin-item">
                <div class="course-admin-main">
                    <div class="course-admin-icon">{{ $loop->iteration }}</div>
                    <div class="course-admin-info">
                        <div class="course-admin-title-row"><h2>{{ $answer->question->question_text }}</h2><span class="cms-status {{ $answer->is_correct?'is-published':'is-draft' }}"><span></span>{{ $answer->is_correct?'Correcta':'Incorrecta' }}</span></div>
                        <p><strong>Tu respuesta:</strong> {{ $answer->selectedOption?->option_text ?? 'Sin respuesta' }}</p>
                        @unless($answer->is_correct)<p><strong>Respuesta correcta:</strong> {{ $correctOption?->option_text }}</p>@endunless
                        @if($answer->question->explanation)<p><strong>Retroalimentación:</strong> {{ $answer->question->explanation }}</p>@endif
                    </div>
                </div>
            </article>
        @endforeach
    </section>
</div>
@endsection