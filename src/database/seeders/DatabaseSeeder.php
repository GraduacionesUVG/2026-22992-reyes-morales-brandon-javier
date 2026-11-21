<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Administrador
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(

            [
                'email' =>
                    'admin@ejemplo.com',
            ],

            [
                'name' =>
                    'Administrador',

                'password' =>
                    Hash::make('Admin12345!'),

                'role' =>
                    User::ROLE_ADMIN,

                'email_verified_at' =>
                    now(),
            ]

        );


        /*
        |--------------------------------------------------------------------------
        | Gestor de contenido
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(

            [
                'email' =>
                    'gestor@ejemplo.com',
            ],

            [
                'name' =>
                    'Gestor de contenido',

                'password' =>
                    Hash::make('Gestor12345!'),

                'role' =>
                    User::ROLE_GESTOR,

                'email_verified_at' =>
                    now(),
            ]

        );


        /*
        |--------------------------------------------------------------------------
        | Estudiante
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(

            [
                'email' =>
                    'estudiante@ejemplo.com',
            ],

            [
                'name' =>
                    'Estudiante de prueba',

                'password' =>
                    Hash::make(
                        'Estudiante12345!'
                    ),

                'role' =>
                    User::ROLE_ESTUDIANTE,

                'email_verified_at' =>
                    now(),
            ]

        );
        $this->call([
            CourseSeeder::class,
        ]);
    }
}