<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Edit Dentist Profile') }} — {{ $dentist->full_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6 transition-colors duration-200">

                <div class="mb-6 border-b border-gray-100 dark:border-gray-600 pb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Modify Professional Record</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Update personal details, contact channels, or local clinic address rows.</p>
                </div>

                <!-- 💡 Form layout maps binary file configurations correctly -->
                <form action="{{ route('dentists.update', $dentist->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- 📸 Profile Image Management Section -->
                    <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl border border-gray-100 dark:border-gray-600 flex flex-col sm:flex-row items-center gap-4">
                        <div class="shrink-0">
                            @if($dentist->profile_image)
                                <img src="{{ asset('storage/' . $dentist->profile_image) }}" alt="Current Profile" class="w-20 h-20 rounded-full object-cover border-2 border-indigo-100 dark:border-indigo-900/50 shadow-sm">
                            @else
                                <div class="w-20 h-20 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-gray-400 dark:text-gray-500 text-2xl shadow-inner">📸</div>
                            @endif
                        </div>
                        <div class="w-full">
                            <label for="profile_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Update Profile Image</label>

                            <!-- 💡 Added spaces to the accept attribute string to allow normal file selectors to filter natively -->
                            <input type="file"
                                   name="profile_image"
                                   id="profile_image"
                                   accept="image/jpeg, image/png, image/jpg"
                                   class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-1.5 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/40 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/60 transition">

                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Accepted Formats: JPEG, JPG, or PNG (Max size: 10MB). Leave empty to keep the current image.</p>
                            @error('profile_image') <p class="text-red-500 text-xs mt-1 font-medium">⚠️ {{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $dentist->full_name) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('full_name') <p class="text-red-500 text-xs mt-1 font-medium">⚠️ {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="prc_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">PRC Number</label>
                            <input type="text" name="prc_no" id="prc_no" value="{{ old('prc_no', $dentist->prc_no) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('prc_no') <p class="text-red-500 text-xs mt-1 font-medium">⚠️ {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                   value="{{ old('date_of_birth', $dentist->date_of_birth ? \Carbon\Carbon::parse($dentist->date_of_birth)->format('Y-m-d') : '') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('date_of_birth') <p class="text-red-500 text-xs mt-1 font-medium">⚠️ {{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="contact_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                            <input type="text" name="contact_no" id="contact_no" value="{{ old('contact_no', $dentist->contact_no) }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            @error('contact_no') <p class="text-red-500 text-xs mt-1 font-medium">⚠️ {{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="email_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                        <input type="email" name="email_address" id="email_address" value="{{ old('email_address', $dentist->email_address) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        @error('email_address') <p class="text-red-500 text-xs mt-1 font-medium">⚠️ {{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="home_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Home Address</label>
                        <textarea name="home_address" id="home_address" required rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('home_address', $dentist->home_address) }}</textarea>
                        @error('home_address') <p class="text-red-500 text-xs mt-1 font-medium">⚠️ {{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="clinic_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clinic Address</label>
                        <textarea name="clinic_address" id="clinic_address" required rows="2"
                                  class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('clinic_address', $dentist->clinic_address) }}</textarea>
                        @error('clinic_address') <p class="text-red-500 text-xs mt-1 font-medium">⚠️ {{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-3 border-t border-gray-100 dark:border-gray-600 pt-4 mt-8">
                        <a href="{{ route('dentists.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 transition duration-150">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-semibold rounded-md text-white bg-gray-700 hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-700 focus:ring-gray-500 transition duration-150 cursor-pointer">
                            💾 Save Profile Changes
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>