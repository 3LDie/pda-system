<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/jpeg" href="{{ asset('images/pda_logo.jpg') }}">

        <script>
            (function () {
                const storageKey = 'pda-theme';
                const storedTheme = localStorage.getItem(storageKey);
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = storedTheme === 'dark' || storedTheme === 'light'
                    ? storedTheme
                    : (prefersDark ? 'dark' : 'light');

                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.style.colorScheme = theme;
            })();
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Theme Initialization Script to Prevent Flash -->
        <script>
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    @php
        $isDentistPage = request()->routeIs('dentists.*');
    @endphp
    <body class="font-sans antialiased" data-dentist-mode="{{ $isDentistPage ? '1' : '0' }}">
        <div class="min-h-screen bg-gray-100 dark:bg-[#030616] text-slate-900 dark:text-slate-100 transition-colors duration-200">
            @include('layouts.navigation')

            @isset($header)
                <header class="shadow bg-white dark:bg-[#030616] border-b border-gray-200 dark:border-purple-900/40 text-gray-800 dark:text-slate-100 transition-colors duration-200">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }} 
            </main>
        </div>

        @if(session('success') || $errors->any())
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed top-5 right-5 z-50 max-w-md w-full bg-white dark:bg-gray-700 border border-gray-100 dark:border-gray-600 rounded-xl shadow-xl pointer-events-auto overflow-hidden">
            
            <div class="p-4 flex items-start gap-3">
                @if(session('success'))
                    <div class="flex-shrink-0 text-emerald-500 text-xl">✅</div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Operation Successful</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ session('success') }}</p>
                    </div>
                @else
                    <div class="flex-shrink-0 text-rose-500 text-xl">⚠️</div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Validation/Database Alert</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $errors->first() }}</p>
                    </div>
                @endif

                <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white transition focus:outline-none text-xs font-bold font-sans px-1 cursor-pointer">
                    &times;
                </button>
            </div>
            
            <div class="h-1 {{ session('success') ? 'bg-emerald-500' : 'bg-rose-500' }} w-full origin-left"></div>
        </div>
        @endif
    </body>
</html>