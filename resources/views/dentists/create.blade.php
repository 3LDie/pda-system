<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Register New Dentist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('dentists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <!-- Profile Image Upload -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Profile Image</label>
                                <input type="file" 
                                       name="profile_image" 
                                       accept="image/jpeg,image/png,image/jpg"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                                <p class="text-xs text-gray-400 mt-1">Accepted Formats: JPEG, JPG, or PNG (Max size: 2MB)</p>
                                @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="full_name" required value="{{ old('full_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">PRC Number</label>
                                <input type="text" name="prc_no" required value="{{ old('prc_no') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('prc_no') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" name="date_of_birth" required value="{{ old('date_of_birth') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('date_of_birth') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                                <input type="text" name="contact_no" required value="{{ old('contact_no') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('contact_no') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" name="email_address" required value="{{ old('email_address') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('email_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Home Address</label>
                                <textarea name="home_address" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('home_address') }}</textarea>
                                @error('home_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Clinic Address</label>
                                <textarea name="clinic_address" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('clinic_address') }}</textarea>
                                @error('clinic_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Initial PDA Membership Log</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Flexible Membership Year Input -->
                            <div>
                                <label for="membership_year" class="block text-sm font-medium text-gray-700">Membership Year Bracket</label>
                                <input list="year-options" 
                                       name="membership_year" 
                                       id="membership_year" 
                                       value="{{ old('membership_year') }}" 
                                       required 
                                       placeholder="e.g. {{ date('Y') }}-{{ substr(date('Y') + 1, -2) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                
                                <datalist id="year-options">
                                    @php $currentYear = (int) date('Y'); @endphp
                                    @for ($i = -5; $i <= 2; $i++)
                                        @php
                                            $startYear = $currentYear + $i;
                                            $yearOption = "{$startYear}-" . substr($startYear + 1, -2);
                                        @endphp
                                        <option value="{{ $yearOption }}"></option>
                                    @endfor
                                </datalist>
                                @error('membership_year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payment Status</label>
                                <select name="payment_status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="Active" {{ old('payment_status') == 'Active' ? 'selected' : '' }}>Active (Paid)</option>
                                    <option value="Pending" {{ old('payment_status') == 'Pending' ? 'selected' : '' }}>Pending (Unpaid)</option>
                                </select>
                                @error('payment_status') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('dentists.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                            Save Record
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>