<x-guest-layout>
    <div class="mb-4 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-purple-600 text-white mb-2 shadow-md">
            <span class="font-bold text-lg">PDA</span>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            {{ request()->is('admin/*') ? __('Register System Admin') : __('Member Registration') }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ request()->is('admin/*') ? __('Create a new administrative portal account') : __('Create your chapter portal account') }}
        </p>
    </div>

    <form method="POST" action="{{ request()->is('admin/*') ? route('admin.register.store') : route('register') }}" class="space-y-4">
        @csrf

        <!-- Full Name (Surname, First Name, Middle Name) -->
        <div>
            <x-input-label for="full_name" :value="__('Full Name *')" />
            <x-text-input id="full_name" class="block mt-1 w-full text-sm" type="text" name="full_name" :value="old('full_name')" required autofocus placeholder="Surname, First Name, Middle Name" />
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>

        <!-- Name Extension (Optional) -->
        <div>
            <x-input-label for="extension" :value="__('Name Extension (Optional)')" />
            <x-text-input id="extension" class="block mt-1 w-full text-sm" type="text" name="extension" :value="old('extension')" placeholder="e.g. Jr., III, Sr." />
            <x-input-error :messages="$errors->get('extension')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email Address *')" />
            <x-text-input id="email" class="block mt-1 w-full text-sm" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password *')" />
            <x-text-input id="password" class="block mt-1 w-full text-sm"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password *')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full text-sm"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4 pt-2">
            @if(!request()->is('admin/*'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            @else
                <div></div>
            @endif

            <x-primary-button class="ms-4 bg-[#5045EB] hover:bg-[#3c37d2]">
                {{ request()->is('admin/*') ? __('Create Admin') : __('Register') }}
            </x-primary-button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ url('/') }}" class="text-xs text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">← Back to Home</a>
        </div>
    </form>
</x-guest-layout>