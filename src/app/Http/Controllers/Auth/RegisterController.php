<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario
    |--------------------------------------------------------------------------
    */

    public function show(): View
    {
        return view('auth.register');
    }


    /*
    |--------------------------------------------------------------------------
    | Registrar estudiante
    |--------------------------------------------------------------------------
    */

    public function register(
        Request $request
    ): RedirectResponse {

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
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Crear usuario
        |--------------------------------------------------------------------------
        |
        | Todo usuario registrado públicamente será estudiante.
        |
        */

        $user = User::create([

            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'password' =>
                $validated['password'],

            'role' =>
                User::ROLE_ESTUDIANTE,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Iniciar sesión automáticamente
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        $request
            ->session()
            ->regenerate();


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('dashboard');
    }
}