<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario para nueva contraseña
    |--------------------------------------------------------------------------
    */

    public function show(
        Request $request,
        string $token
    ): View {

        return view(
            'auth.reset-password',
            [
                'token' => $token,

                'email' =>
                    $request->query('email'),
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Restablecer contraseña
    |--------------------------------------------------------------------------
    */

    public function reset(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validación
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'token' => [
                'required',
            ],

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8),
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Restablecer contraseña
        |--------------------------------------------------------------------------
        */

        $status = Password::reset(

            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),

            function (
                User $user,
                string $password
            ): void {

                /*
                |--------------------------------------------------------------------------
                | Guardar nueva contraseña
                |--------------------------------------------------------------------------
                */

                $user->forceFill([

                    'password' =>
                        Hash::make($password),

                ]);


                /*
                |--------------------------------------------------------------------------
                | Cambiar remember_token
                |--------------------------------------------------------------------------
                |
                | Esto invalida sesiones persistentes anteriores.
                |
                */

                $user->setRememberToken(
                    Str::random(60)
                );


                $user->save();


                /*
                |--------------------------------------------------------------------------
                | Evento oficial de Laravel
                |--------------------------------------------------------------------------
                */

                event(
                    new PasswordReset($user)
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Contraseña actualizada correctamente
        |--------------------------------------------------------------------------
        */

        if (
            $status === Password::PASSWORD_RESET
        ) {

            return redirect()
                ->route('login')
                ->with(
                    'status',
                    'Tu contraseña fue actualizada correctamente. Ya puedes iniciar sesión.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Error
        |--------------------------------------------------------------------------
        */

        $message = match ($status) {

            Password::INVALID_TOKEN =>
                'El enlace de recuperación no es válido o ya expiró.',

            Password::INVALID_USER =>
                'No se pudo encontrar una cuenta asociada a ese correo.',

            Password::RESET_THROTTLED =>
                'Has realizado demasiados intentos. Espera un momento e inténtalo nuevamente.',

            default =>
                'No fue posible restablecer la contraseña.',

        };


        return back()

            ->withInput(
                $request->only('email')
            )

            ->withErrors([
                'email' => $message,
            ]);
    }
}