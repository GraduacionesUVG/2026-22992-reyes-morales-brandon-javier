@extends('layouts.app')

@section('title','Configuración CMS · '.config('branding.site_name'))

@section('page_title','Configuración CMS')

@section('page_description','Personalización visual y textos principales de la plataforma')

@section('content')
<div class="cms-page cms-form-page">

    <a href="{{ route('admin.dashboard') }}" class="cms-back-link">
        <svg viewBox="0 0 20 20" fill="none">
            <path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Volver al dashboard
    </a>

    <header class="cms-form-heading">
        <p class="cms-eyebrow">Administración</p>
        <h1>Configuración CMS</h1>
        <span>Administra nombre, colores, logo, textos del login y apariencia general.</span>
    </header>

    @if(session('status'))
        <div class="cms-alert-success">
            <span>✓</span>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="cms-form-card">
        @csrf
        @method('PUT')

        <div class="cms-form-card-header">
            <div>
                <h2>Identidad general</h2>
                <p>Información principal que identifica la plataforma.</p>
            </div>
        </div>

        <div class="cms-form-card-body">
            <div class="cms-form-grid">

                <div class="cms-field">
                    <label for="site_name">Nombre de la plataforma</label>
                    <input id="site_name" name="site_name" type="text" value="{{ old('site_name',$settings['site_name']) }}" required>
                    @error('site_name')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="site_short_name">Nombre corto</label>
                    <input id="site_short_name" name="site_short_name" type="text" value="{{ old('site_short_name',$settings['site_short_name']) }}" maxlength="12" required>
                    <small>Se usa cuando no hay logo. Ejemplo: AV.</small>
                    @error('site_short_name')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field cms-field-full">
                    <label for="site_description">Descripción corta</label>
                    <textarea id="site_description" name="site_description" rows="3">{{ old('site_description',$settings['site_description']) }}</textarea>
                    @error('site_description')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field cms-field-full">
                    <label for="logo">Logo del portal</label>

                    @if(!empty($settings['logo_path']))
                        <div class="cms-current-media">
                            <img src="{{ asset('storage/'.$settings['logo_path']) }}" alt="Logo actual">
                            <label>
                                <input type="checkbox" name="remove_logo" value="1">
                                Quitar logo actual
                            </label>
                        </div>
                    @endif

                    <input id="logo" name="logo" type="file" accept=".png,.jpg,.jpeg,.webp,.svg">
                    <small>Formatos permitidos: PNG, JPG, JPEG, WEBP o SVG. Máximo 2 MB.</small>
                    @error('logo')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        <div class="cms-form-card-header">
            <div>
                <h2>Apariencia visual</h2>
                <p>Colores separados para evitar interferencias entre botones, dashboard, tarjetas y menú.</p>
            </div>
        </div>

        <div class="cms-form-card-body">
            <div class="cms-form-grid">

                <div class="cms-field">
                    <label for="primary_color">Color principal</label>
                    <input id="primary_color" name="primary_color" type="color" value="{{ old('primary_color',$settings['primary_color']) }}" required>
                    <small>Botones principales y acciones importantes.</small>
                    @error('primary_color')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="accent_color">Color secundario</label>
                    <input id="accent_color" name="accent_color" type="color" value="{{ old('accent_color',$settings['accent_color']) }}" required>
                    <small>Títulos y textos destacados.</small>
                    @error('accent_color')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="background_color">Color de fondo</label>
                    <input id="background_color" name="background_color" type="color" value="{{ old('background_color',$settings['background_color']) }}" required>
                    <small>Fondo general de páginas internas.</small>
                    @error('background_color')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="text_color">Color de texto</label>
                    <input id="text_color" name="text_color" type="color" value="{{ old('text_color',$settings['text_color']) }}" required>
                    <small>Texto principal del sistema.</small>
                    @error('text_color')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="card_color">Color de tarjetas</label>
                    <input id="card_color" name="card_color" type="color" value="{{ old('card_color',$settings['card_color'] ?? '#ffffff') }}" required>
                    <small>Cards, formularios y paneles.</small>
                    @error('card_color')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="stat_color">Color de estadísticas</label>
                    <input id="stat_color" name="stat_color" type="color" value="{{ old('stat_color',$settings['stat_color'] ?? '#2563eb') }}" required>
                    <small>Barras y números del dashboard.</small>
                    @error('stat_color')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="sidebar_active_color">Fondo menú activo</label>
                    <input id="sidebar_active_color" name="sidebar_active_color" type="color" value="{{ old('sidebar_active_color',$settings['sidebar_active_color'] ?? '#eaf2ff') }}" required>
                    <small>Fondo de la opción seleccionada.</small>
                    @error('sidebar_active_color')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="sidebar_active_text_color">Texto menú activo</label>
                    <input id="sidebar_active_text_color" name="sidebar_active_text_color" type="color" value="{{ old('sidebar_active_text_color',$settings['sidebar_active_text_color'] ?? '#2563eb') }}" required>
                    <small>Texto e ícono de la opción activa.</small>
                    @error('sidebar_active_text_color')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="success_color">Color de éxito</label>
                    <input id="success_color" name="success_color" type="color" value="{{ old('success_color',$settings['success_color'] ?? '#16a34a') }}" required>
                    <small>Alertas positivas y mensajes de confirmación.</small>
                    @error('success_color')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="font_family">Tipografía</label>
                    <select id="font_family" name="font_family" required>
                        @foreach($fonts as $font=>$label)
                            <option value="{{ $font }}" @selected(old('font_family',$settings['font_family'])===$font)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('font_family')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="upload_limit_mb">Límite de carga por archivo</label>
                    <input id="upload_limit_mb" name="upload_limit_mb" type="number" min="1" max="999" value="{{ old('upload_limit_mb',$settings['upload_limit_mb']) }}" required>
                    <small>Valor en MB. Se usa en recursos de lecciones.</small>
                    @error('upload_limit_mb')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        <div class="cms-form-card-header">
            <div>
                <h2>Login</h2>
                <p>Textos y contenido que aparecen en la pantalla de inicio de sesión.</p>
            </div>
        </div>

        <div class="cms-form-card-body">
            <div class="cms-form-grid">

                <div class="cms-field">
                    <label for="login_badge">Etiqueta superior</label>
                    <input id="login_badge" name="login_badge" type="text" value="{{ old('login_badge',$settings['login_badge']) }}">
                    @error('login_badge')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_button_text">Texto del botón</label>
                    <input id="login_button_text" name="login_button_text" type="text" value="{{ old('login_button_text',$settings['login_button_text']) }}">
                    @error('login_button_text')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field cms-field-full">
                    <label for="login_title">Título del login</label>
                    <input id="login_title" name="login_title" type="text" value="{{ old('login_title',$settings['login_title']) }}">
                    @error('login_title')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field cms-field-full">
                    <label for="login_subtitle">Subtítulo del login</label>
                    <textarea id="login_subtitle" name="login_subtitle" rows="3">{{ old('login_subtitle',$settings['login_subtitle']) }}</textarea>
                    @error('login_subtitle')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_email_label">Etiqueta del correo</label>
                    <input id="login_email_label" name="login_email_label" type="text" value="{{ old('login_email_label',$settings['login_email_label']) }}">
                    @error('login_email_label')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_email_placeholder">Placeholder del correo</label>
                    <input id="login_email_placeholder" name="login_email_placeholder" type="text" value="{{ old('login_email_placeholder',$settings['login_email_placeholder']) }}">
                    @error('login_email_placeholder')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_password_label">Etiqueta de contraseña</label>
                    <input id="login_password_label" name="login_password_label" type="text" value="{{ old('login_password_label',$settings['login_password_label']) }}">
                    @error('login_password_label')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_password_placeholder">Placeholder de contraseña</label>
                    <input id="login_password_placeholder" name="login_password_placeholder" type="text" value="{{ old('login_password_placeholder',$settings['login_password_placeholder']) }}">
                    @error('login_password_placeholder')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_remember_label">Texto recordarme</label>
                    <input id="login_remember_label" name="login_remember_label" type="text" value="{{ old('login_remember_label',$settings['login_remember_label']) }}">
                    @error('login_remember_label')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_forgot_text">Texto recuperar contraseña</label>
                    <input id="login_forgot_text" name="login_forgot_text" type="text" value="{{ old('login_forgot_text',$settings['login_forgot_text']) }}">
                    @error('login_forgot_text')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_register_text">Texto registro</label>
                    <input id="login_register_text" name="login_register_text" type="text" value="{{ old('login_register_text',$settings['login_register_text']) }}">
                    @error('login_register_text')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_register_link_text">Texto link registro</label>
                    <input id="login_register_link_text" name="login_register_link_text" type="text" value="{{ old('login_register_link_text',$settings['login_register_link_text']) }}">
                    @error('login_register_link_text')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        <div class="cms-form-card-header">
            <div>
                <h2>Panel visual del login</h2>
                <p>Contenido del lado visual de la pantalla de login.</p>
            </div>
        </div>

        <div class="cms-form-card-body">
            <div class="cms-form-grid">

                <div class="cms-field cms-field-full">
                    <label for="login_side_title">Título del panel visual</label>
                    <input id="login_side_title" name="login_side_title" type="text" value="{{ old('login_side_title',$settings['login_side_title']) }}">
                    @error('login_side_title')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field cms-field-full">
                    <label for="login_side_subtitle">Subtítulo del panel visual</label>
                    <textarea id="login_side_subtitle" name="login_side_subtitle" rows="3">{{ old('login_side_subtitle',$settings['login_side_subtitle']) }}</textarea>
                    @error('login_side_subtitle')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_feature_1">Característica 1</label>
                    <input id="login_feature_1" name="login_feature_1" type="text" value="{{ old('login_feature_1',$settings['login_feature_1']) }}">
                    @error('login_feature_1')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_feature_2">Característica 2</label>
                    <input id="login_feature_2" name="login_feature_2" type="text" value="{{ old('login_feature_2',$settings['login_feature_2']) }}">
                    @error('login_feature_2')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_feature_3">Característica 3</label>
                    <input id="login_feature_3" name="login_feature_3" type="text" value="{{ old('login_feature_3',$settings['login_feature_3']) }}">
                    @error('login_feature_3')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field">
                    <label for="login_feature_4">Característica 4</label>
                    <input id="login_feature_4" name="login_feature_4" type="text" value="{{ old('login_feature_4',$settings['login_feature_4']) }}">
                    @error('login_feature_4')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field cms-field-full">
                    <label for="login_footer_text">Texto inferior</label>
                    <input id="login_footer_text" name="login_footer_text" type="text" value="{{ old('login_footer_text',$settings['login_footer_text']) }}">
                    @error('login_footer_text')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

                <div class="cms-field cms-field-full">
                    <label for="login_image">Imagen decorativa del login</label>

                    @if(!empty($settings['login_image_path']))
                        <div class="cms-current-media">
                            <img src="{{ asset('storage/'.$settings['login_image_path']) }}" alt="Imagen actual del login">
                            <label>
                                <input type="checkbox" name="remove_login_image" value="1">
                                Quitar imagen actual
                            </label>
                        </div>
                    @endif

                    <input id="login_image" name="login_image" type="file" accept=".png,.jpg,.jpeg,.webp,.svg">
                    <small>Formatos permitidos: PNG, JPG, JPEG, WEBP o SVG. Máximo 4 MB.</small>
                    @error('login_image')<p class="cms-field-error">{{ $message }}</p>@enderror
                </div>

            </div>
        </div>

        <div class="cms-form-actions">
            <a href="{{ route('admin.dashboard') }}" class="cms-secondary-button">Cancelar</a>
            <button type="submit" class="cms-primary-button">Guardar configuración</button>
        </div>

    </form>

</div>
@endsection

@push('styles')
<style>
.cms-current-media{display:flex;align-items:center;gap:14px;margin-bottom:14px;padding:12px;border:1px solid rgba(15,23,42,.1);border-radius:16px;background:#f8fafc}
.cms-current-media img{width:92px;height:92px;object-fit:contain;border-radius:16px;background:#fff;border:1px solid rgba(15,23,42,.08);padding:8px}
.cms-current-media label{display:flex;align-items:center;gap:8px;margin:0;font-weight:600;color:#475569}
.cms-current-media input{width:auto}
.cms-form-card-header{border-top:1px solid rgba(15,23,42,.08);padding-top:24px;margin-top:24px}
.cms-form-card-header:first-child{border-top:0;padding-top:0;margin-top:0}
.cms-form-card-header h2{margin:0;color:#0f172a;font-size:1.2rem}
.cms-form-card-header p{margin:6px 0 0;color:#64748b}
</style>
@endpush