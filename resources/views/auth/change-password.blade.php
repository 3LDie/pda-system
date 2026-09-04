<x-guest-layout>

    <div class="text-center mb-6">
        <img
            src="{{ asset('images/pda_logo.jpg') }}"
            alt="PDA Logo"
            class="w-16 h-16 mx-auto mb-4 rounded-full object-cover"
>
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
            Change Your Password
        </h2>

        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            Enter your new password below
        </p>
    </div>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        @method('PATCH')

        <!-- New Password -->
        <div>
            <x-input-label
                for="password"
                :value="__('New Password')"
                class="text-slate-700 dark:text-slate-300 font-medium"
            />

            <x-text-input
                id="password"
                class="block mt-1 w-full rounded-xl border-slate-300
                       dark:border-slate-700 dark:bg-slate-900 dark:text-white
                       focus:border-purple-600 focus:ring-purple-600 shadow-sm"
                type="password"
                name="password"
                required
                autocomplete="new-password"
            />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label
                for="password_confirmation"
                :value="__('Confirm New Password')"
                class="text-slate-700 dark:text-slate-300 font-medium"
            />

            <x-text-input
                id="password_confirmation"
                class="block mt-1 w-full rounded-xl border-slate-300
                       dark:border-slate-700 dark:bg-slate-900 dark:text-white
                       focus:border-purple-600 focus:ring-purple-600 shadow-sm"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
            />
        </div>

        <!-- Button -->
        <div class="mt-6 flex justify-end">
            <x-primary-button
                class="bg-purple-700 hover:bg-purple-800
                       focus:bg-purple-800 active:bg-purple-900
                       focus:ring-purple-500
                       rounded-xl px-6 py-3 transition shadow-sm"
            >
                Update Password
            </x-primary-button>
        </div>

    </form>

</x-guest-layout>