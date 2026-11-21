@extends('layouts.app')
@section('title','Responder simulador · '.config('branding.site_name'))
@section('page_title','Simulador')
@section('page_description',$module->title)

@section('content')
<div class="cms-page cms-form-page">
    <a href="{{ route('student.quiz.index') }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none"><path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Volver al simulador
    </a>

    <header class="cms-form-heading"><p class="cms-eyebrow">{{ $module->course->title }}</p><h1>{{ $module->title }}</h1><span>Responde todas las preguntas. Al finalizar verás tu resultado y retroalimentación.</span></header>

    <form method="POST" action="{{ route('student.quiz.submit',$module) }}" class="cms-form-card">
        @csrf
        <div class="cms-form-card-header"><div><h2>Preguntas del módulo</h2><p>Total de preguntas: {{ $questions->count() }}</p></div></div>
        <div class="cms-form-card-body">
            @foreach($questions as $question)
                @php $letters=['A','B','C','D']; @endphp
                <div class="cms-field cms-field-full" style="padding:18px;border:1px solid rgba(0,0,0,.08);border-radius:18px;background:#fff;">
                    <label>{{ $loop->iteration }}. {{ $question->question_text }}</label>
                    <div style="display:grid;gap:10px;margin-top:12px;">
                        @foreach($question->options as $option)
                            <label style="display:flex;gap:10px;align-items:flex-start;padding:12px;border:1px solid rgba(0,0,0,.08);border-radius:14px;cursor:pointer;">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required @checked((int)old('answers.'.$question->id)===$option->id) style="width:auto;margin-top:3px;">
                                <span><strong>{{ $letters[$loop->index] ?? '' }}.</strong> {{ $option->option_text }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('answers.'.$question->id)<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>
            @endforeach
        </div>
        <footer class="cms-form-actions">
            <a href="{{ route('student.quiz.index') }}" class="cms-cancel-button">Cancelar</a>
            <button type="submit" class="cms-primary-button">Finalizar simulador</button>
        </footer>
    </form>
</div>
@endsection