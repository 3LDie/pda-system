<x-guest-layout>
    <div class="mb-6 text-center">
        <div class="w-12 h-12 bg-purple-700 mx-auto rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-sm border border-amber-400/40 mb-3">
            PDA
        </div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Member Registration</h2>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Create your chapter portal account</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" class="text-slate-700 dark:text-slate-300 font-medium" />
            <x-text-input id="name" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-purple-600 focus:ring-purple-600 shadow-sm" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-slate-700 dark:text-slate-300 font-medium" />
            <x-text-input id="email" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-purple-600 focus:ring-purple-600 shadow-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-slate-700 dark:text-slate-300 font-medium" />

            <x-text-input id="password" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-purple-600 focus:ring-purple-600 shadow-sm"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-700 dark:text-slate-300 font-medium" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-xl border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:border-purple-600 focus:ring-purple-600 shadow-sm"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-slate-600 dark:text-slate-400 hover:text-purple-600" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="bg-purple-700 hover:bg-purple-800 focus:bg-purple-800 active:bg-purple-900 focus:ring-purple-500 rounded-xl px-6 py-3 transition shadow-sm">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ url('/') }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-purple-600 transition">
                &larr; Back to Home
            </a>
        </div>
    </form>
</x-guest-layout>