<div class="cms-form-card-header">
    <div>
        <h2>Datos del usuario</h2>
        <p>Completa la información requerida.</p>
    </div>
</div>

<div class="cms-form-card-body">
    <div class="cms-form-grid">
        <div class="cms-field">
            <label for="name">Nombre</label>
            <input id="name" name="name" type="text" value="{{ old('name',$user->name) }}" placeholder="Nombre completo" required>
            @error('name')<p class="cms-field-error">{{ $message }}</p>@enderror
        </div>

        <div class="cms-field">
            <label for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" value="{{ old('email',$user->email) }}" placeholder="usuario@correo.com" required>
            @error('email')<p class="cms-field-error">{{ $message }}</p>@enderror
        </div>

        <div class="cms-field">
            <label for="role">Rol</label>
            <select id="role" name="role" required>
                @foreach($roles as $value=>$label)
                    <option value="{{ $value }}" @selected(old('role',$user->role)===$value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('role')<p class="cms-field-error">{{ $message }}</p>@enderror
        </div>

        <div class="cms-field">
            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password" placeholder="{{ $showPasswordHelp?'Dejar vacío para no cambiar':'Mínimo 8 caracteres' }}" {{ $showPasswordHelp?'':'required' }}>
            @if($showPasswordHelp)<small>Deja este campo vacío si no deseas cambiar la contraseña.</small>@endif
            @error('password')<p class="cms-field-error">{{ $message }}</p>@enderror
        </div>

        <div class="cms-field">
            <label for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Repetir contraseña" {{ $showPasswordHelp?'':'required' }}>
        </div>
    </div>
</div>

<footer class="cms-form-actions">
    <a href="{{ route('admin.users.index') }}" class="cms-cancel-button">Cancelar</a>
    <button type="submit" class="cms-primary-button">{{ $buttonText }}</button>
</footer>