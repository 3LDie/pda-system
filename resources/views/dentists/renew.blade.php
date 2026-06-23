<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Renew Membership Year') }} — {{ $dentist->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="mb-6 border-b border-gray-100 pb-4">
                    <h3 class="text-lg font-medium text-gray-900">Log New Fiscal Subscription</h3>
                    <p class="text-sm text-gray-500">PRC License Number: <span class="font-mono font-bold text-gray-700">{{ $dentist->prc_no }}</span></p>
                </div>

                <form action="{{ route('dentists.storeRenewal', $dentist->id) }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Membership Year Bracket</label>
                        <select name="membership_year" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">-- Select Subscription Year --</option>
                            @php
                                $currentYear = date('Y');
                            @endphp
                            @for ($i = $currentYear + 1; $i >= 2020; $i--)
                                @php 
                                    $bracket = $i . '-' . substr($i + 1, -2); 
                                @endphp
                                <option value="{{ $bracket }}" {{ old('membership_year') == $bracket ? 'selected' : '' }}>
                                    {{ $bracket }}
                                </option>
                            @endfor
                        </select>
                        @error('membership_year')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <select name="payment_status" required 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="Active (Paid)" {{ old('payment_status') == 'Active (Paid)' ? 'selected' : '' }}>Active (Paid)</option>
                            <option value="Inactive (Unpaid)" {{ old('payment_status') == 'Inactive (Unpaid)' ? 'selected' : '' }}>Inactive (Unpaid)</option>
                            <option value="Pending" {{ old('payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('payment_status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-4 mt-4">
                        <a href="{{ route('dentists.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition mr-2">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition cursor-pointer">
                            💾 Confirm Renewal Log
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>