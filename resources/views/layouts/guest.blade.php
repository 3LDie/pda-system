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
        
        <!-- Transparent Favicon Link -->
        <link rel="icon" type="image/png" href="{{ asset('images/pda-logo.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 relative overflow-hidden">
            
            <!-- 🌟 Visible, Transparent Full-Screen Background Logo Watermark -->
            <div class="absolute inset-0 z-0 pointer-events-none flex items-center justify-center opacity-[0.15]">
                <img src="{{ asset('images/pda-logo.png') }}" alt="Watermark" class="w-auto h-[70vh] object-contain max-w-full">
            </div>

            <div class="z-10 flex flex-col items-center w-full">
                <div>
                    <a href="/">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                </div>

                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>