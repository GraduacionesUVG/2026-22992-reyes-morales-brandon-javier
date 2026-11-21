<div class="cms-form-grid">
    <div class="cms-field cms-field-full">
        <label for="title">Título del curso</label>
        <input id="title" name="title" type="text" value="{{ old('title',$course->title ?? '') }}" placeholder="Ej. Aprendizaje teórico de conducción" required autofocus>
        @error('title')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field cms-field-full">
        <label for="description">Descripción</label>
        <textarea id="description" name="description" rows="5" placeholder="Describe brevemente el propósito y contenido del curso.">{{ old('description',$course->description ?? '') }}</textarea>
        <small>Esta descripción será visible para el estudiante.</small>
        @error('description')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field">
        <label for="status">Estado</label>
        <select id="status" name="status" required>
            <option value="draft" @selected(old('status',$course->status ?? 'draft')==='draft')>Borrador</option>
            <option value="published" @selected(old('status',$course->status ?? 'draft')==='published')>Publicado</option>
        </select>
        <small>Solo los cursos publicados son visibles para estudiantes.</small>
        @error('status')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field">
        <label for="sort_order">Orden</label>
        <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order',$course->sort_order ?? 1) }}" required>
        <small>Define la posición del curso dentro de la plataforma.</small>
        @error('sort_order')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>
</div>