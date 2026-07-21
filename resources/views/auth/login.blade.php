<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-12 h-12 bg-purple-700 mx-auto rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-sm border border-amber-400/40 mb-3">
            PDA
        </div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">PDA Portal Login</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Enter your credentials to access your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700 dark:text-slate-300 font-medium" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-purple-600 focus:ring-purple-600 shadow-sm" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-slate-700 dark:text-slate-300 font-medium" />

            <x-text-input id="password" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-purple-600 focus:ring-purple-600 shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 dark:border-slate-700 text-purple-600 shadow-sm focus:ring-purple-500 dark:bg-slate-900" name="remember">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-400">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-purple-600 dark:text-purple-400 hover:underline" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="mt-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-purple-600 transition">
                &larr; Back to Home
            </a>

            <x-primary-button class="bg-purple-700 hover:bg-purple-800 focus:bg-purple-800 active:bg-purple-900 focus:ring-purple-500 rounded-xl px-6 py-3 transition shadow-sm">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>