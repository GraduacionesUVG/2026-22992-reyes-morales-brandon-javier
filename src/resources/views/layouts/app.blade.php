
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('branding.site_description') }}">
    <title>@yield('title', config('branding.site_name'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @include('partials.theme')

    <style>
        :root {
            --color-primary: {{ config('branding.colors.primary', '#244784') }};
            --color-primary-hover: {{ config('branding.colors.primary_hover', '#1D3A70') }};
            --color-accent: {{ config('branding.colors.accent', '#0071E3') }};
            --color-background: {{ config('branding.colors.background', '#F5F5F7') }};
            --color-surface: {{ config('branding.colors.surface', '#FFFFFF') }};
            --color-text: {{ config('branding.colors.text', '#1D1D1F') }};
            --color-muted: {{ config('branding.colors.muted', '#6E6E73') }};
            --color-border: {{ config('branding.colors.border', '#D2D2D7') }};
            --color-sidebar: {{ config('branding.colors.sidebar', '#FFFFFF') }};
        }
    </style>

    @stack('styles')
    @include('partials.clarity')
</head>

<body class="app-body">
    <div class="app-shell">
        @include('partials.sidebar')

        <div class="app-main">
            @include('partials.navbar')

            <main class="app-content">
                <div class="app-content-inner">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <div id="sidebarOverlay" class="app-sidebar-overlay" aria-hidden="true"></div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
