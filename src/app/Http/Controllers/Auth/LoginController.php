<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Mostrar formulario de inicio de sesión
    |--------------------------------------------------------------------------
    */

    public function show(): View
    {
        return view('auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | Procesar inicio de sesión
    |--------------------------------------------------------------------------
    */

    public function login(Request $request): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Validar credenciales
        |--------------------------------------------------------------------------
        */

        $credentials = $request->validate([

            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Intentar autenticar
        |--------------------------------------------------------------------------
        */

        if (
            Auth::attempt(
                $credentials,
                $request->boolean('remember')
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | Regenerar sesión
            |--------------------------------------------------------------------------
            |
            | Esto evita ataques de fijación de sesión.
            |
            */

            $request
                ->session()
                ->regenerate();


            /*
            |--------------------------------------------------------------------------
            | Eliminar una URL "intended" antigua
            |--------------------------------------------------------------------------
            |
            | Si anteriormente el usuario intentó entrar a una ruta
            | protegida, Laravel pudo guardar esa dirección.
            |
            | Por ejemplo:
            |
            | /estudiante/dashboard
            |
            | No queremos que un administrador sea enviado allí.
            |
            */

            $request
                ->session()
                ->forget('url.intended');


            /*
            |--------------------------------------------------------------------------
            | Ir al dashboard general
            |--------------------------------------------------------------------------
            |
            | /dashboard determinará el destino correcto según el rol:
            |
            | admin      -> /admin/dashboard
            | gestor     -> /gestor/dashboard
            | estudiante -> /estudiante/dashboard
            |
            */

            return redirect()
                ->route('dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | Credenciales incorrectas
        |--------------------------------------------------------------------------
        */

        return back()

            ->withErrors([

                'email' =>
                    'Las credenciales ingresadas no son correctas.',

            ])

            ->onlyInput('email');
    }


    /*
    |--------------------------------------------------------------------------
    | Cerrar sesión
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();


        /*
        |--------------------------------------------------------------------------
        | Invalidar sesión
        |--------------------------------------------------------------------------
        */

        $request
            ->session()
            ->invalidate();


        /*
        |--------------------------------------------------------------------------
        | Generar nuevo token CSRF
        |--------------------------------------------------------------------------
        */

        $request
            ->session()
            ->regenerateToken();


        /*
        |--------------------------------------------------------------------------
        | Volver al login
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login');
    }
}