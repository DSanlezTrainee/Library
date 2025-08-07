<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="h-full">
    <div class="min-h-full">
        <!-- Navbar -->
        <nav class="bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <img src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500"
                                alt="Your Company" class="size-8" />
                        </div>
                        <div>
                            <div class="ml-10 flex items-baseline space-x-4">
                                <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                                <x-nav-link href="/books" :active="request()->is('books')">Books</x-nav-link>
                                <x-nav-link href="/authors" :active="request()->is('authors')">Authors</x-nav-link>
                                <x-nav-link href="/publishers" :active="request()->is('publishers')">Publishers
                                </x-nav-link>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="ml-4 flex items-center md:ml-6">
                            @if (Route::has('login'))
                            <div class="flex items-center justify-end gap-4">
                                @auth
                                <a href="{{ url('/dashboard') }}"
                                    class="inline-block px-5 py-1.5 border border-gray-300 hover:border-gray-400 text-white rounded-sm text-sm leading-normal">
                                    Dashboard
                                </a>
                                @else
                                <a href="{{ route('login') }}"
                                    class="inline-block px-5 py-1.5 text-white border border-transparent hover:border-gray-300 rounded-sm text-sm leading-normal">
                                    Log in
                                </a>
                                @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-block px-5 py-1.5 border border-gray-300 hover:border-gray-400 text-white rounded-sm text-sm leading-normal">
                                    Register
                                </a>
                                @endif
                                @endauth
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Heading -->
        <header class="bg-white shadow-sm">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $heading ?? '' }}</h1>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
    @if (Route::has('login'))
    <div class="h-14.5 hidden lg:block"></div>
    @endif
    @livewireScripts

</body>

</html>