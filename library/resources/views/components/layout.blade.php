<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    @vite('resources/css/app.css')
    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                            <img src="https://static.vecteezy.com/system/resources/previews/020/402/234/non_2x/library-book-reading-abstract-icon-or-emblem-vector.jpg"
                                alt="Your Company" class="size-12" />
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
                                {{-- User Dropdown --}}
                                <div class="ms-3 relative ">
                                    <x-dropdown align="right" width="48"
                                        contentClasses="py-1 bg-gray-900 text-white rounded-md ring-1 ring-black ring-opacity-5">
                                        <x-slot name="trigger">
                                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                            <button
                                                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                                <img class="size-8 rounded-full object-cover"
                                                    src="{{ Auth::user()->profile_photo_url }}"
                                                    alt="{{ Auth::user()->name }}" />
                                            </button>
                                            @else
                                            <span class="inline-flex rounded-md">
                                                <button type="button"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                    {{ Auth::user()->name }}
                                                    <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg"
                                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                    </svg>
                                                </button>
                                            </span>
                                            @endif
                                        </x-slot>

                                        <x-slot name="content">
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('Manage Account') }}
                                            </div>

                                            <x-dropdown-link class="text-white hover:bg-gray-700"
                                                href="{{ route('profile.show') }}">
                                                {{ __('Profile') }}
                                            </x-dropdown-link>

                                            <x-dropdown-link class="text-white hover:bg-gray-700"
                                                href="{{ route('dashboard') }}">
                                                {{ __('Dashboard') }}
                                            </x-dropdown-link>

                                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                            <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                                {{ __('API Tokens') }}
                                            </x-dropdown-link>
                                            @endif

                                            <div class="border-t border-gray-200"></div>

                                            <form method="POST" action="{{ route('logout') }}" x-data>
                                                @csrf
                                                <x-dropdown-link class="text-white hover:bg-gray-700"
                                                    href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                                    {{ __('Log Out') }}
                                                </x-dropdown-link>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                </div>
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