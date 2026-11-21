<div class="cms-form-grid">
    <div class="cms-field cms-field-full">
        <label>Curso</label>
        <input type="text" value="{{ $course->title }}" disabled>
        <small>El módulo pertenecerá a este curso.</small>
    </div>

    <div class="cms-field cms-field-full">
        <label for="title">Título del módulo</label>
        <input id="title" name="title" type="text" value="{{ old('title',$module->title ?? '') }}" placeholder="Ej. Señalización vial" required autofocus>
        @error('title')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field cms-field-full">
        <label for="description">Descripción</label>
        <textarea id="description" name="description" rows="5" placeholder="Describe brevemente los temas que se estudiarán.">{{ old('description',$module->description ?? '') }}</textarea>
        @error('description')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field">
        <label for="status">Estado</label>
        <select id="status" name="status" required>
            <option value="draft" @selected(old('status',$module->status ?? 'draft')==='draft')>Borrador</option>
            <option value="published" @selected(old('status',$module->status ?? 'draft')==='published')>Publicado</option>
        </select>
        <small>Solo los módulos publicados serán visibles para estudiantes.</small>
        @error('status')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field">
        <label for="sort_order">Orden</label>
        <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order',$module->sort_order ?? ($course->modules()->max('sort_order')+1)) }}" required>
        <small>Define la posición del módulo dentro del curso.</small>
        @error('sort_order')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>
</div>