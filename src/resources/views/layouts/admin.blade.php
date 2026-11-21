<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Plataforma Educativa')
    </title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @livewireStyles

</head>

<body class="bg-slate-100">

    <div class="min-h-screen">

        @include('partials.navbar')

        <div class="flex">

            @include('partials.sidebar')

            <main class="min-h-[calc(100vh-64px)] flex-1 p-6">

                @yield('content')

            </main>

        </div>

    </div>

    @livewireScripts

</body>

</html>