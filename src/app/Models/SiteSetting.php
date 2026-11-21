<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable=['key','value'];

    public static function defaults(): array{
        return [
            'site_name'=>config('branding.site_name','AutoEscuela Virtual'),
            'site_short_name'=>config('branding.site_short_name','AV'),
            'site_description'=>'Plataforma web para el aprendizaje teórico de conducción.',
            'logo_path'=>null,
            'login_image_path'=>null,
            'primary_color'=>'#2563eb',
            'accent_color'=>'#0f172a',
            'background_color'=>'#f8fafc',
            'text_color'=>'#0f172a',
            'card_color'=>'#ffffff',
            'stat_color'=>'#2563eb',
            'sidebar_active_color'=>'#eaf2ff',
            'sidebar_active_text_color'=>'#2563eb',
            'success_color'=>'#16a34a',
            'font_family'=>'Inter',
            'login_badge'=>'Plataforma educativa',
            'login_title'=>'Bienvenido a AutoEscuela Virtual',
            'login_subtitle'=>'Ingresa tus credenciales para continuar con tu aprendizaje teórico de conducción.',
            'login_email_label'=>'Correo electrónico',
            'login_email_placeholder'=>'tu-correo@ejemplo.com',
            'login_password_label'=>'Contraseña',
            'login_password_placeholder'=>'Ingresa tu contraseña',
            'login_remember_label'=>'Recordarme',
            'login_forgot_text'=>'¿Olvidaste tu contraseña?',
            'login_button_text'=>'Iniciar sesión',
            'login_register_text'=>'¿No tienes una cuenta?',
            'login_register_link_text'=>'Crear cuenta',
            'login_side_title'=>'Aprende teoría vial de forma clara, segura y organizada',
            'login_side_subtitle'=>'Estudia módulos, revisa lecciones, consulta recursos de apoyo y practica con el simulador de preguntas.',
            'login_feature_1'=>'Contenido organizado por módulos.',
            'login_feature_2'=>'Lecciones con recursos de apoyo.',
            'login_feature_3'=>'Seguimiento de progreso.',
            'login_feature_4'=>'Simulador de preguntas.',
            'login_footer_text'=>'Aprendizaje teórico de conducción para automóviles particulares en Guatemala.',
            'upload_limit_mb'=>'50',
        ];
    }

    public static function fonts(): array{
        return [
            'Inter'=>'Inter / sistema',
            'Arial'=>'Arial',
            'Helvetica'=>'Helvetica',
            'Georgia'=>'Georgia',
            'Verdana'=>'Verdana',
            'Tahoma'=>'Tahoma',
            'Trebuchet MS'=>'Trebuchet MS',
        ];
    }

    public static function getValue(string $key,?string $default=null): ?string{
        return static::where('key',$key)->value('value') ?? $default;
    }

    public static function setValue(string $key,?string $value): void{
        static::updateOrCreate(['key'=>$key],['value'=>$value]);
    }

    public static function allSettings(): array{
        $settings=static::defaults();

        try{
            foreach(static::query()->pluck('value','key') as $key=>$value){
                if(array_key_exists($key,$settings)){
                    $settings[$key]=$value;
                }
            }
        }catch(\Throwable $e){}

        return $settings;
    }

    public static function uploadLimitMb(): int{
        $value=(int) static::getValue('upload_limit_mb',static::defaults()['upload_limit_mb']);
        return max(1,min($value,500));
    }

    public static function uploadLimitKb(): int{
        return static::uploadLimitMb()*1024;
    }

    public static function uploadLimitHuman(): string{
        return static::uploadLimitMb().' MB';
    }
}