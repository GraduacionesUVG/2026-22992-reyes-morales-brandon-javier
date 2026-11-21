<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Prueba Tailwind
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="min-h-screen bg-slate-100">

    <main class="mx-auto max-w-5xl px-6 py-16">

        <div class="rounded-2xl bg-white p-10 shadow-lg">

            <span
                class="rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-700"
            >
                Laravel 13
            </span>


            <h1
                class="mt-6 text-4xl font-bold tracking-tight text-slate-900"
            >
                Plataforma educativa funcionando
            </h1>


            <p
                class="mt-4 text-lg text-slate-600"
            >
                Laravel, PostgreSQL, Blade y Tailwind CSS
                están funcionando correctamente.
            </p>


            <button
                class="mt-8 rounded-xl bg-blue-600 px-5 py-3 font-semibold text-white transition hover:bg-blue-700"
            >
                Botón de prueba
            </button>

        </div>

    </main>

</body>

</html>