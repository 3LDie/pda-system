<x-app-layout>
    <x-slot name="header">
<<<<<<< .merge_file_6ZXKN0
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
=======
<<<<<<< HEAD

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">

        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
(Update Register New Dentist view to match dark theme styling)
=======
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
>>>>>>> 2ef7221 (Refactor dentist CRUD views for dynamic light/dark mode, fix email binding in edit view, and remove photo column from directory index)
>>>>>>> .merge_file_nFSHbN
            {{ __('Register New Dentist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
<<<<<<< .merge_file_6ZXKN0
            <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6 transition-colors duration-200">

                <form action="{{ route('dentists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="border-b border-gray-200 dark:border-gray-600 pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Personal Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Image</label>
                                <input type="file"
                                       name="profile_image"
                                       accept="image/jpeg,image/png,image/jpg"
                                       class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/40 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/60 transition">
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Accepted Formats: JPEG, JPG, or PNG (Max size: 2MB)</p>
                                @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
=======
<<<<<<< HEAD

            <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6 transition-colors duration-200">

                <form action="{{ route('dentists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="border-b border-gray-200 dark:border-gray-600 pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Personal Details</h3>

            <div class="bg-gray-900 border border-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 text-gray-100">
=======
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
>>>>>>> 2ef7221 (Refactor dentist CRUD views for dynamic light/dark mode, fix email binding in edit view, and remove photo column from directory index)
                
                <form action="{{ route('dentists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

<<<<<<< HEAD
                    <div class="border-b border-gray-800 pb-4">
                        <h3 class="text-lg font-medium text-white mb-4">Personal Details</h3>
 (Update Register New Dentist view to match dark theme styling)
=======
                    <div class="border-b border-gray-200 dark:border-gray-800 pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Personal Details</h3>
>>>>>>> 2ef7221 (Refactor dentist CRUD views for dynamic light/dark mode, fix email binding in edit view, and remove photo column from directory index)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="md:col-span-2">
<<<<<<< HEAD

                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Image</label>
                                <input type="file"
                                       name="profile_image"
                                       accept="image/jpeg,image/png,image/jpg"
                                       class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/40 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/60 transition">
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Accepted Formats: JPEG, JPG, or PNG (Max size: 2MB)</p>
                                @error('profile_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
=======
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Image</label>
                                <input type="file" 
                                       name="profile_image" 
                                       accept="image/jpeg,image/png,image/jpg"
                                       class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-gray-800 file:text-indigo-700 dark:file:text-indigo-400 hover:file:bg-indigo-100 dark:hover:file:bg-gray-700 transition">
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Accepted Formats: JPEG, JPG, or PNG (Max size: 2MB)</p>
                                @error('profile_image') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
>>>>>>> 2ef7221 (Refactor dentist CRUD views for dynamic light/dark mode, fix email binding in edit view, and remove photo column from directory index)
>>>>>>> .merge_file_nFSHbN
                            </div>

                            <!-- Full Name (Surname, First Name, Middle Name) -->
                            <div>
<<<<<<< .merge_file_6ZXKN0
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
=======
<<<<<<< HEAD
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
<<<<<<< HEAD
>>>>>>> .merge_file_nFSHbN
                                <input type="text" name="full_name" required value="{{ old('full_name') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('full_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">PRC Number</label>
                                <input type="text" name="prc_no" required value="{{ old('prc_no') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('prc_no') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth</label>
                                <input type="date" name="date_of_birth" required value="{{ old('date_of_birth') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('date_of_birth') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number</label>
                                <input type="text" name="contact_no" required value="{{ old('contact_no') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('contact_no') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                                <input type="email" name="email_address" required value="{{ old('email_address') }}" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('email_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Home Address</label>
                                <textarea name="home_address" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('home_address') }}</textarea>
                                @error('home_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clinic Address</label>
                                <textarea name="clinic_address" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('clinic_address') }}</textarea>
                                @error('clinic_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                                <label class="block text-sm font-medium text-gray-300">Profile Image</label>
                                <input type="file" 
                                       name="profile_image" 
                                       accept="image/jpeg,image/png,image/jpg"
                                       class="mt-1 block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-800 file:text-indigo-400 hover:file:bg-gray-700 transition">
                                <p class="text-xs text-gray-500 mt-1">Accepted Formats: JPEG, JPG, or PNG (Max size: 2MB)</p>
                                @error('profile_image') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300">Full Name</label>
                                <input type="text" name="full_name" required value="{{ old('full_name') }}" class="mt-1 block w-full rounded-md bg-gray-800 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('full_name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">PRC Number</label>
                                <input type="text" name="prc_no" required value="{{ old('prc_no') }}" class="mt-1 block w-full rounded-md bg-gray-800 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('prc_no') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Date of Birth</label>
                                <input type="date" name="date_of_birth" required value="{{ old('date_of_birth') }}" class="mt-1 block w-full rounded-md bg-gray-800 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('date_of_birth') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300">Contact Number</label>
                                <input type="text" name="contact_no" required value="{{ old('contact_no') }}" class="mt-1 block w-full rounded-md bg-gray-800 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('contact_no') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300">Email Address</label>
                                <input type="email" name="email_address" required value="{{ old('email_address') }}" class="mt-1 block w-full rounded-md bg-gray-800 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('email_address') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300">Home Address</label>
                                <textarea name="home_address" required class="mt-1 block w-full rounded-md bg-gray-800 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('home_address') }}</textarea>
                                @error('home_address') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300">Clinic Address</label>
                                <textarea name="clinic_address" required class="mt-1 block w-full rounded-md bg-gray-800 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('clinic_address') }}</textarea>
                                @error('clinic_address') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
 (Update Register New Dentist view to match dark theme styling)
=======
                                <input type="text" name="full_name" required value="{{ old('full_name') }}" class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
=======
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="full_name" required value="{{ old('full_name') }}" placeholder="Surname, First Name, Middle Name" class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
>>>>>>> 811fac3 (Refactor dentist name structuring with auto-parsed middle initials, separate extension fields, and updated form validation requirements)
                                @error('full_name') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Name Extension (Optional) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name Extension <span class="text-gray-400 text-xs">(Optional)</span></label>
                                <input type="text" name="extension" value="{{ old('extension') }}" placeholder="e.g. Jr., III, Sr." class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @error('extension') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">PRC Number <span class="text-red-500">*</span></label>
                                <input type="text" name="prc_no" required value="{{ old('prc_no') }}" class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @error('prc_no') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth <span class="text-red-500">*</span></label>
                                <input type="date" name="date_of_birth" required value="{{ old('date_of_birth') }}" class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @error('date_of_birth') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Number <span class="text-red-500">*</span></label>
                                <input type="text" name="contact_no" required value="{{ old('contact_no') }}" class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @error('contact_no') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email_address" required value="{{ old('email_address') }}" class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @error('email_address') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Home Address <span class="text-red-500">*</span></label>
                                <textarea name="home_address" required class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('home_address') }}</textarea>
                                @error('home_address') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clinic Address <span class="text-red-500">*</span></label>
                                <textarea name="clinic_address" required class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('clinic_address') }}</textarea>
                                @error('clinic_address') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
>>>>>>> 2ef7221 (Refactor dentist CRUD views for dynamic light/dark mode, fix email binding in edit view, and remove photo column from directory index)
                            </div>
                        </div>
                    </div>

                    <div>
<<<<<<< .merge_file_6ZXKN0
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Initial PDA Membership Log</h3>
=======
<<<<<<< HEAD

                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Initial PDA Membership Log</h3>

                        <h3 class="text-lg font-medium text-white mb-4">Initial PDA Membership Log</h3>
 (Update Register New Dentist view to match dark theme styling)
=======
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Initial PDA Membership Log</h3>
>>>>>>> 2ef7221 (Refactor dentist CRUD views for dynamic light/dark mode, fix email binding in edit view, and remove photo column from directory index)
>>>>>>> .merge_file_nFSHbN
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <!-- Flexible Membership Year Input -->
                            <div>
<<<<<<< .merge_file_6ZXKN0
=======
<<<<<<< HEAD
<<<<<<< HEAD

>>>>>>> .merge_file_nFSHbN
                                <label for="membership_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Membership Year Bracket</label>
                                <input list="year-options"
                                       name="membership_year"
                                       id="membership_year"
                                       value="{{ old('membership_year') }}"
                                       required
<<<<<<< .merge_file_6ZXKN0
                                       placeholder="e.g. {{ date('Y') }}-{{ substr(date('Y') + 1, -2) }}"
                                       autocomplete="off"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">

=======
                                       placeholder="e.g. {{ date('Y') }}-{{ substr(date('Y') + 1, -2) }}"
                                       autocomplete="off"
                                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">

                                <label for="membership_year" class="block text-sm font-medium text-gray-300">Membership Year Bracket</label>
=======
                                <label for="membership_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Membership Year Bracket</label>
>>>>>>> 2ef7221 (Refactor dentist CRUD views for dynamic light/dark mode, fix email binding in edit view, and remove photo column from directory index)
=======
                                <label for="membership_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Membership Year Bracket <span class="text-red-500">*</span></label>
>>>>>>> 811fac3 (Refactor dentist name structuring with auto-parsed middle initials, separate extension fields, and updated form validation requirements)
                                <input list="year-options" 
                                       name="membership_year" 
                                       id="membership_year" 
                                       value="{{ old('membership_year') }}" 
                                       required 
                                       placeholder="e.g. {{ date('Y') }}-{{ substr(date('Y') + 1, -2) }}"
                                       autocomplete="off"
<<<<<<< HEAD
                                       class="mt-1 block w-full rounded-md bg-gray-800 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
=======
                                       class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
>>>>>>> 2ef7221 (Refactor dentist CRUD views for dynamic light/dark mode, fix email binding in edit view, and remove photo column from directory index)
                                
(Update Register New Dentist view to match dark theme styling)
>>>>>>> .merge_file_nFSHbN
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
<<<<<<< HEAD
                                @error('membership_year') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>

                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label>
                                <select name="payment_status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">

                                <label class="block text-sm font-medium text-gray-300">Payment Status</label>
                                <select name="payment_status" required class="mt-1 block w-full rounded-md bg-gray-800 border-gray-700 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
 (Update Register New Dentist view to match dark theme styling)
                                    <option value="Active" {{ old('payment_status') == 'Active' ? 'selected' : '' }}>Active (Paid)</option>
                                    <option value="Pending" {{ old('payment_status') == 'Pending' ? 'selected' : '' }}>Pending (Unpaid)</option>
                                </select>
                                @error('payment_status') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
=======
                                @error('membership_year') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
<<<<<<< .merge_file_6ZXKN0
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status</label>
                                <select name="payment_status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
=======
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Status <span class="text-red-500">*</span></label>
                                <select name="payment_status" required class="mt-1 block w-full rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
>>>>>>> .merge_file_nFSHbN
                                    <option value="Active" {{ old('payment_status') == 'Active' ? 'selected' : '' }}>Active (Paid)</option>
                                    <option value="Pending" {{ old('payment_status') == 'Pending' ? 'selected' : '' }}>Pending (Unpaid)</option>
                                </select>
                                @error('payment_status') <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
>>>>>>> 2ef7221 (Refactor dentist CRUD views for dynamic light/dark mode, fix email binding in edit view, and remove photo column from directory index)
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
<<<<<<< .merge_file_6ZXKN0
                        <a href="{{ route('dentists.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#5045EB] dark:bg-[#2b2b2e] focus:outline-none transition">
=======
<<<<<<< HEAD

                        <a href="{{ route('dentists.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#5045EB] dark:bg-[#2b2b2e] focus:outline-none transition">

                        <a href="{{ route('dentists.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-300 bg-gray-800 hover:bg-gray-700 focus:outline-none">
=======
                        <a href="{{ route('dentists.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none">
>>>>>>> 2ef7221 (Refactor dentist CRUD views for dynamic light/dark mode, fix email binding in edit view, and remove photo column from directory index)
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
 (Update Register New Dentist view to match dark theme styling)
>>>>>>> .merge_file_nFSHbN
                            Save Record
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>