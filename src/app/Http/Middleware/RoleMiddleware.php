<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! in_array($user->role, $roles, true)) {
            $dashboard = match ($user->role) {
                User::ROLE_ADMIN => 'admin.dashboard',
                User::ROLE_GESTOR => 'gestor.dashboard',
                User::ROLE_ESTUDIANTE => 'student.dashboard',
                default => null,
            };

            if ($dashboard) {
                return redirect()->route($dashboard);
            }

            abort(
                403,
                'El usuario no tiene un rol válido.'
            );
        }

        return $next($request);
    }
}