<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SiteSettingController extends Controller
{
    public function edit(): View{
        $settings=SiteSetting::allSettings();
        $fonts=SiteSetting::fonts();

        return view('admin.settings.edit',[
            'settings'=>$settings,
            'fonts'=>$fonts
        ]);
    }

    public function update(Request $request): RedirectResponse{
        $validated=$request->validate([
            'site_name'=>['required','string','max:120'],
            'site_short_name'=>['required','string','max:12'],
            'site_description'=>['nullable','string','max:255'],
            'primary_color'=>['required','regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color'=>['required','regex:/^#[0-9A-Fa-f]{6}$/'],
            'background_color'=>['required','regex:/^#[0-9A-Fa-f]{6}$/'],
            'text_color'=>['required','regex:/^#[0-9A-Fa-f]{6}$/'],
            'card_color'=>['required','regex:/^#[0-9A-Fa-f]{6}$/'],
            'stat_color'=>['required','regex:/^#[0-9A-Fa-f]{6}$/'],
            'sidebar_active_color'=>['required','regex:/^#[0-9A-Fa-f]{6}$/'],
            'sidebar_active_text_color'=>['required','regex:/^#[0-9A-Fa-f]{6}$/'],
            'success_color'=>['required','regex:/^#[0-9A-Fa-f]{6}$/'],
            'font_family'=>['required','string','max:80'],
            'login_badge'=>['nullable','string','max:100'],
            'login_title'=>['nullable','string','max:180'],
            'login_subtitle'=>['nullable','string','max:350'],
            'login_email_label'=>['nullable','string','max:100'],
            'login_email_placeholder'=>['nullable','string','max:160'],
            'login_password_label'=>['nullable','string','max:100'],
            'login_password_placeholder'=>['nullable','string','max:160'],
            'login_remember_label'=>['nullable','string','max:100'],
            'login_forgot_text'=>['nullable','string','max:160'],
            'login_button_text'=>['nullable','string','max:100'],
            'login_register_text'=>['nullable','string','max:160'],
            'login_register_link_text'=>['nullable','string','max:100'],
            'login_side_title'=>['nullable','string','max:220'],
            'login_side_subtitle'=>['nullable','string','max:450'],
            'login_feature_1'=>['nullable','string','max:180'],
            'login_feature_2'=>['nullable','string','max:180'],
            'login_feature_3'=>['nullable','string','max:180'],
            'login_feature_4'=>['nullable','string','max:180'],
            'login_footer_text'=>['nullable','string','max:220'],
            'upload_limit_mb'=>['required','integer','min:1','max:999'],
            'logo'=>['nullable','file','mimes:png,jpg,jpeg,webp,svg','max:2048'],
            'login_image'=>['nullable','file','mimes:png,jpg,jpeg,webp,svg','max:4096'],
            'remove_logo'=>['nullable','boolean'],
            'remove_login_image'=>['nullable','boolean'],
        ],[
            'site_name.required'=>'El nombre de la plataforma es obligatorio.',
            'site_short_name.required'=>'El nombre corto es obligatorio.',
            'primary_color.regex'=>'El color principal debe tener formato hexadecimal, por ejemplo #2563eb.',
            'accent_color.regex'=>'El color secundario debe tener formato hexadecimal, por ejemplo #0f172a.',
            'background_color.regex'=>'El color de fondo debe tener formato hexadecimal, por ejemplo #f8fafc.',
            'text_color.regex'=>'El color de texto debe tener formato hexadecimal, por ejemplo #0f172a.',
            'card_color.regex'=>'El color de tarjetas debe tener formato hexadecimal, por ejemplo #ffffff.',
            'stat_color.regex'=>'El color de estadísticas debe tener formato hexadecimal, por ejemplo #2563eb.',
            'sidebar_active_color.regex'=>'El fondo del menú activo debe tener formato hexadecimal, por ejemplo #eaf2ff.',
            'sidebar_active_text_color.regex'=>'El texto del menú activo debe tener formato hexadecimal, por ejemplo #2563eb.',
            'success_color.regex'=>'El color de éxito debe tener formato hexadecimal, por ejemplo #16a34a.',
            'logo.mimes'=>'El logo debe ser PNG, JPG, JPEG, WEBP o SVG.',
            'logo.max'=>'El logo no debe pesar más de 2 MB.',
            'login_image.mimes'=>'La imagen del login debe ser PNG, JPG, JPEG, WEBP o SVG.',
            'login_image.max'=>'La imagen del login no debe pesar más de 4 MB.',
            'upload_limit_mb.required'=>'El límite de carga es obligatorio.',
            'upload_limit_mb.min'=>'El límite mínimo es 1 MB.',
            'upload_limit_mb.max'=>'El límite máximo es 500 MB.',
        ]);

        $currentLogo=SiteSetting::getValue('logo_path');
        $currentLoginImage=SiteSetting::getValue('login_image_path');

        if($request->boolean('remove_logo')&&$currentLogo){
            Storage::disk('public')->delete($currentLogo);
            SiteSetting::setValue('logo_path',null);
        }

        if($request->boolean('remove_login_image')&&$currentLoginImage){
            Storage::disk('public')->delete($currentLoginImage);
            SiteSetting::setValue('login_image_path',null);
        }

        if($request->hasFile('logo')){
            if($currentLogo){
                Storage::disk('public')->delete($currentLogo);
            }

            $path=$request->file('logo')->store('branding','public');
            SiteSetting::setValue('logo_path',$path);
        }

        if($request->hasFile('login_image')){
            if($currentLoginImage){
                Storage::disk('public')->delete($currentLoginImage);
            }

            $path=$request->file('login_image')->store('branding','public');
            SiteSetting::setValue('login_image_path',$path);
        }

        unset(
            $validated['logo'],
            $validated['login_image'],
            $validated['remove_logo'],
            $validated['remove_login_image']
        );

        foreach($validated as $key=>$value){
            SiteSetting::setValue($key,$value);
        }

        return back()->with('status','Configuración CMS actualizada correctamente.');
    }
}