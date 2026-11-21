<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Iniciar sesión</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="min-h-screen bg-slate-100">

    <div class="flex min-h-screen items-center justify-center px-6">

        <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-lg">

            <h1 class="text-3xl font-bold text-slate-900">
                Iniciar sesión
            </h1>

            <p class="mt-2 text-slate-500">
                Ingresa tus credenciales para continuar.
            </p>


            <form
                method="POST"
                action="{{ route('login.submit') }}"
                class="mt-8 space-y-5"
            >

                @csrf


                <div>

                    <label
                        for="email"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Correo electrónico
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500"
                    >

                    @error('email')

                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>

                    @enderror

                </div>


                <div>

                    <label
                        for="password"
                        class="block text-sm font-medium text-slate-700"
                    >
                        Contraseña
                    </label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 outline-none focus:border-blue-500"
                    >

                </div>


                <label class="flex items-center gap-2">

                    <input
                        type="checkbox"
                        name="remember"
                        class="rounded border-slate-300"
                    >

                    <span class="text-sm text-slate-600">
                        Recordarme
                    </span>

                </label>


                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white hover:bg-blue-700"
                >
                    Iniciar sesión
                </button>

            </form>

        </div>

    </div>

</body>

</html>