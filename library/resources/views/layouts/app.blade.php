<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @livewireScripts

    <script>
        // Adiciona event listener para atualizar o menu após mudar a foto de perfil
        document.addEventListener('livewire:init', () => {
            Livewire.on('refresh-navigation-menu', () => {
                // Força o navegador a buscar uma nova versão da imagem de perfil
                const profileImages = document.querySelectorAll('img[src*="profile_photo"]');
                profileImages.forEach(img => {
                    const timestamp = new Date().getTime();
                    const currentSrc = img.src;
                    
                    if (currentSrc.includes('?')) {
                        img.src = currentSrc.split('?')[0] + '?v=' + timestamp;
                    } else {
                        img.src = currentSrc + '?v=' + timestamp;
                    }
                });
            });
        });
    </script>
</body>

</html>