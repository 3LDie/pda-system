<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
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

                    <!-- Membership Year: Flexible Input using Datalist -->
                    <div>
                        <label for="membership_year" class="block text-sm font-medium text-gray-700">Membership Year Bracket</label>
                        <input list="year-options" 
                               name="membership_year" 
                               id="membership_year" 
                               value="{{ old('membership_year') }}" 
                               required 
                               placeholder="e.g. {{ date('Y') }}-{{ substr(date('Y') + 1, -2) }}"
                               class="mt-1 block w-full rounded-md shadow-sm transition duration-150 ease-in-out text-sm @error('membership_year') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">

                        <datalist id="year-options">
                            @php $currentYear = (int)date('Y'); @endphp
                            @for ($i = $currentYear + 2; $i >= 2020; $i--)
                                @php $bracket = $i . '-' . substr($i + 1, -2); @endphp
                                <option value="{{ $bracket }}"></option>
                            @endfor
                        </datalist>

                        @error('membership_year')
                            <p class="text-red-500 text-xs mt-1 font-medium">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Status -->
                    <div>
                        <label for="payment_status" class="block text-sm font-medium text-gray-700">Payment Status</label>
                        <select name="payment_status" id="payment_status" required 
                                class="mt-1 block w-full rounded-md shadow-sm transition duration-150 ease-in-out text-sm @error('payment_status') border-red-300 focus:border-red-500 focus:ring-red-500 @else border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 @enderror">
                            <option value="Active (Paid)" {{ old('payment_status') == 'Active (Paid)' ? 'selected' : '' }}>Active (Paid)</option>
                            <option value="Unpaid" {{ old('payment_status') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="Pending" {{ old('payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                        @error('payment_status')
                            <p class="text-red-500 text-xs mt-1 font-medium">⚠️ {{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 border-t border-gray-100 pt-4 mt-8">
                        <a href="{{ route('dentists.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-md text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 cursor-pointer">
                            💾 Confirm Renewal Log
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>