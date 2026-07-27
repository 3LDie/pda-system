<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6 transition-colors duration-200">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Welcome Administrator!</h1>
            </div>
        </div>
    </div>
</x-app-layout>