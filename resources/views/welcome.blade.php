@php
if (auth()->check()) {
    header('Location: ' . route('dentists.index'));
    exit;
}
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PDA Information Management System - Baguio City Chapter</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased min-h-screen flex flex-col justify-between selection:bg-purple-600 selection:text-white transition-colors duration-200">

    <!-- Header Navigation -->
    <header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <!-- Official Chapter Logo Seal -->
                <img src="{{ asset('images/pda_logo.jpg') }}" alt="PDA Logo" class="w-11 h-11 rounded-full object-cover shadow-sm border border-purple-500/30">
                <div>
                    <h1 class="text-sm sm:text-base font-bold text-slate-900 dark:text-white leading-tight">Philippine Dental Association</h1>
                    <p class="text-xs text-purple-700 dark:text-purple-400 font-semibold tracking-wide">Baguio City Chapter</p>
                </div>
            </div>

            <nav class="flex items-center space-x-4">
                <!-- Dark Mode Toggle Button -->
                <button @click="darkMode = !darkMode" class="p-2.5 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition border border-slate-200 dark:border-slate-700 shadow-sm" aria-label="Toggle Dark Mode">
                    <!-- Moon Icon -->
                    <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <!-- Sun Icon -->
                    <svg x-show="darkMode" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                </button>

                @auth
                    <a href="{{ route('dentists.index') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-purple-700 hover:bg-purple-800 rounded-xl shadow-sm transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('register.page') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-purple-700 hover:bg-purple-800 rounded-xl shadow-sm transition">
                        Register
                    </a>
                @endauth
            </nav>
        </div>
    </header>



</div>
<!-- Main Content Section -->
<main class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16 w-full overflow-hidden">

    <!-- Background Logo -->
    <div class="absolute inset-0 flex justify-center pointer-events-none z-0 overflow-hidden">
        <img
            src="{{ asset('images/pda_logo.jpg') }}"
            alt=""
            class="absolute w-[700px] max-w-none opacity-15 object-contain"
            style="top: -0px;"
        >
    </div>

    <!-- Main Content -->
    <div class="relative z-10">

        <!-- Heading -->

    <!-- Main Content Section -->
<main class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16 w-full overflow-hidden">

    <!-- Transparent Background Logo -->
    <div class="absolute inset-0 flex justify-center items-center pointer-events-none z-0">
        <img
            src="{{ asset('images/pda_logo.jpg') }}"
            alt=""
            class="w-[600px] max-w-[70vw] opacity-20 object-contain"
        >
    </div>

    <!-- All Content -->
    <div class="relative z-10">

        <!-- Heading Section -->
 (Update landing page design and add backgorund logo)
        <div class="text-center max-w-3xl mx-auto mb-16">

            <span class="inline-block px-3.5 py-1 text-xs font-semibold tracking-wider
                         text-purple-700 dark:text-purple-300 uppercase
                         bg-purple-50 dark:bg-purple-950/40
                         border border-purple-200 dark:border-purple-900/50
                         rounded-full mb-4">
                Digital Chapter Portal
            </span>

            <h2 class="text-4xl sm:text-5xl font-extrabold
                       text-slate-900 dark:text-white
                       tracking-tight mb-6">
                Streamlining Member Records & Practice Tracking
            </h2>

            <p class="text-lg text-slate-600 dark:text-slate-300 leading-relaxed">
                Welcome to the official local information management portal.
                Securely track multi-year membership statuses, manage chapter
                rosters, and maintain professional dentist files.
            </p>

        </div>

        <!-- Portal Cards -->
        <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">


            <!-- Admin Portal -->
            <div class="bg-white dark:bg-slate-900
                        rounded-2xl border border-slate-200/80 dark:border-slate-800

            <!-- Administrator Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl
                        border border-slate-200/80 dark:border-slate-800
 (Update landing page design and add backgorund logo)
                        shadow-sm p-8 flex flex-col justify-between
                        hover:shadow-md transition">

                <div>

                    <div class="w-12 h-12
                                bg-purple-50 dark:bg-purple-950/50
                                text-purple-700 dark:text-purple-300
                                rounded-xl flex items-center justify-center
                                font-bold text-xl mb-6 shadow-inner
                                border border-purple-100 dark:border-purple-900">

                    <div class="w-12 h-12 bg-purple-50 dark:bg-purple-950/50
                                text-purple-700 dark:text-purple-300 rounded-xl
                                flex items-center justify-center font-bold text-xl
                                mb-6 shadow-inner border border-purple-100
                                dark:border-purple-900">
(Update landing page design and add backgorund logo)
                        🛡️
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">
                        Administrator Gateway
                    </h3>

                    <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed mb-6">
                        Manage local chapter rows, trace automated multi-year
                        membership statuses, register new dentists, export
                        rosters, and oversee system audit logs.
                    </p>
                </div>

                <a href="{{ route('admin.login') }}"
                   class="w-full text-center py-3 px-4
                          bg-slate-900 dark:bg-slate-800
                          hover:bg-slate-800 dark:hover:bg-slate-700

                          text-white font-medium rounded-xl transition shadow-sm">

                          text-white font-medium rounded-xl
                          transition shadow-sm">
 (Update landing page design and add backgorund logo)
                    Access Admin Portal
                </a>

            </div>


            <!-- Member Portal -->
            <div class="bg-white dark:bg-slate-900
                        rounded-2xl border border-slate-200/80 dark:border-slate-800

            <!-- Member Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl
                        border border-slate-200/80 dark:border-slate-800
 (Update landing page design and add backgorund logo)
                        shadow-sm p-8 flex flex-col justify-between
                        hover:shadow-md transition">

                <div>

                    <div class="w-12 h-12
                                bg-emerald-50 dark:bg-emerald-950/50
                                text-emerald-600 dark:text-emerald-400
                                rounded-xl flex items-center justify-center
                                font-bold text-xl mb-6 shadow-inner
                                border border-emerald-100 dark:border-emerald-900">

                    <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/50
                                text-emerald-600 dark:text-emerald-400 rounded-xl
                                flex items-center justify-center font-bold text-xl
                                mb-6 shadow-inner border border-emerald-100
                                dark:border-emerald-900">
 (Update landing page design and add backgorund logo)
                        🦷
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">
                        Member Portal
                    </h3>

                    <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed mb-6">
                        View professional dentist credentials, check active
                        standing verification, track membership logs, and keep
                        clinic location details updated.
                    </p>
                </div>

                <a href="{{ route('member.login') }}"
                   class="w-full text-center py-3 px-4
                          bg-purple-700 hover:bg-purple-800

                          text-white font-medium rounded-xl transition shadow-sm">

                          text-white font-medium rounded-xl
                          transition shadow-sm">
 (Update landing page design and add backgorund logo)
                    Access Member Portal
                </a>

            </div>

        </div>

    </div>

</main>


    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-slate-500 dark:text-slate-400">
            &copy; {{ date('Y') }} Philippine Dental Association - Baguio City Chapter. All rights reserved.
        </div>
    </footer>

</body>
</html>