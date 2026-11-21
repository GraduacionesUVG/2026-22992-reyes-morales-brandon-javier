@php
$letters=['A','B','C','D'];
$optionValues=old('options',$optionValues ?? ['','','','']);
$correctOption=(int)old('correct_option',$correctOptionIndex ?? 0);
@endphp

<div class="cms-form-grid">
    <div class="cms-field cms-field-full">
        <label>Módulo</label>
        <input type="text" value="{{ $module->title }}" disabled>
        <small>La pregunta pertenecerá a este módulo.</small>
    </div>

    <div class="cms-field cms-field-full">
        <label for="question_text">Pregunta</label>
        <textarea id="question_text" name="question_text" rows="4" placeholder="Ej. ¿Qué documento debe portar una persona para conducir legalmente?" required autofocus>{{ old('question_text',$question->question_text ?? '') }}</textarea>
        @error('question_text')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field cms-field-full">
        <label>Opciones de respuesta</label>
        <small>Agrega exactamente 4 opciones y marca cuál es la respuesta correcta.</small>
    </div>

    @for($i=0;$i<4;$i++)
        <div class="cms-field cms-field-full">
            <label for="option_{{ $i }}">Opción {{ $letters[$i] }}</label>
            <div style="display:flex;gap:12px;align-items:center;">
                <input type="radio" name="correct_option" value="{{ $i }}" @checked($correctOption===$i) style="width:auto;min-width:18px;">
                <input id="option_{{ $i }}" name="options[{{ $i }}]" type="text" value="{{ $optionValues[$i] ?? '' }}" placeholder="Escribe la opción {{ $letters[$i] }}" required>
            </div>
            <small>Selecciona el círculo si esta opción es la correcta.</small>
            @error('options.'.$i)<p class="cms-field-error">{{ $message }}</p>@enderror
        </div>
    @endfor

    @error('options')<div class="cms-field cms-field-full"><p class="cms-field-error">{{ $message }}</p></div>@enderror
    @error('correct_option')<div class="cms-field cms-field-full"><p class="cms-field-error">{{ $message }}</p></div>@enderror

    <div class="cms-field cms-field-full">
        <label for="explanation">Retroalimentación</label>
        <textarea id="explanation" name="explanation" rows="4" placeholder="Explica brevemente por qué esa respuesta es correcta.">{{ old('explanation',$question->explanation ?? '') }}</textarea>
        <small>Esta explicación se mostrará al estudiante después de responder el simulador.</small>
        @error('explanation')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field">
        <label for="points">Puntos</label>
        <input id="points" name="points" type="number" min="1" max="10" value="{{ old('points',$question->points ?? 1) }}" required>
        @error('points')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field">
        <label for="sort_order">Orden</label>
        <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order',$question->sort_order ?? $suggestedSortOrder) }}" required>
        @error('sort_order')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field cms-field-full">
        <label for="status">Estado</label>
        <select id="status" name="status" required>
            <option value="draft" @selected(old('status',$question->status ?? 'draft')==='draft')>Borrador</option>
            <option value="published" @selected(old('status',$question->status ?? 'draft')==='published')>Publicado</option>
        </select>
        <small>Solo las preguntas publicadas aparecerán en el simulador del estudiante.</small>
        @error('status')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>
</div>