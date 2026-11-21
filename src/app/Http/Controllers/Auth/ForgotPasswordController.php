<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario de recuperación
    |--------------------------------------------------------------------------
    */

    public function show(): View
    {
        return view('auth.forgot-password');
    }


    /*
    |--------------------------------------------------------------------------
    | Enviar enlace de recuperación
    |--------------------------------------------------------------------------
    */

    public function sendResetLink(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validar correo
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Solicitar a Laravel el envío del enlace
        |--------------------------------------------------------------------------
        |
        | Laravel:
        |
        | 1. Busca al usuario por email.
        | 2. Genera un token seguro.
        | 3. Guarda el token en password_reset_tokens.
        | 4. Envía el correo.
        |
        */

        Password::sendResetLink(
            $request->only('email')
        );


        /*
        |--------------------------------------------------------------------------
        | Respuesta
        |--------------------------------------------------------------------------
        |
        | Se utiliza un mensaje genérico para no revelar si un correo
        | determinado existe o no dentro de la plataforma.
        |
        */

        return back()->with(
            'status',
            'Si existe una cuenta asociada a ese correo, recibirás un enlace para restablecer tu contraseña.'
        );
    }
}