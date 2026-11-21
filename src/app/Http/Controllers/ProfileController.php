<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mostrar perfil
    |--------------------------------------------------------------------------
    */

    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar información personal
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request
    ): RedirectResponse {

        $user = $request->user();


        /*
        |--------------------------------------------------------------------------
        | Validación
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Actualizar usuario
        |--------------------------------------------------------------------------
        */

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Regresar al perfil
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'profile_status',
            'Tu información personal se actualizó correctamente.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Cambiar contraseña
    |--------------------------------------------------------------------------
    */

    public function updatePassword(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validación
        |--------------------------------------------------------------------------
        */

        $validated = $request->validateWithBag(
            'updatePassword',
            [

                'current_password' => [
                    'required',
                    'current_password',
                ],

                'password' => [
                    'required',
                    'confirmed',

                    Password::min(8)
                        ->letters()
                        ->numbers(),
                ],

            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Actualizar contraseña
        |--------------------------------------------------------------------------
        */

        $request->user()->update([

            'password' =>
                Hash::make(
                    $validated['password']
                ),

        ]);


        /*
        |--------------------------------------------------------------------------
        | Regresar al perfil
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'password_status',
            'Tu contraseña se actualizó correctamente.'
        );
    }
}