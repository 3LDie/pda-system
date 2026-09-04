<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ $role === 'admin' ? __('Admin Dashboard') : __('My Member Portal') }}
        </h2>
    </x-slot>

    <!-- Dynamic Theme Wrapper: Adapts seamlessly between light and dark modes -->
    <div class="py-12 bg-gray-100 dark:bg-[#030616] min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if($role === 'admin')
                {{-- Admin View Landing State --}}
                <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-xl sm:rounded-2xl p-6 border border-gray-200 dark:border-purple-900/40 text-gray-900 dark:text-gray-100 transition-colors duration-200">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Welcome Back, Administrator!</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Use the navigation bar above to manage Chapter rows or provision administrative system accounts.</p>
                </div>
            @else
                {{-- 🔒 Non-Admin (Member) Portal Layout --}}
<<<<<<< .merge_file_JU6Y4l
 
                <div class="bg-white dark:bg-gray-700 dark:text-gray-100 overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 dark:border-gray-600 p-8">

                <div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-slate-100 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-purple-900/40 p-8 transition-colors duration-200">
=======

                <div class="bg-white dark:bg-gray-700 dark:text-gray-100 overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 dark:border-gray-600 p-8">
                <div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-slate-100 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-200 dark:border-purple-900/40 p-8 transition-colors duration-200">
<<<<<<< HEAD
 (Update layout theme switching and support for light/dark mode across dashboard and dentist directory)
>>>>>>> .merge_file_ljzA8C
                    <div class="flex flex-col md:flex-row items-center gap-8">
=======
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8">
>>>>>>> 638a0ff (Fix 403 authorization error on member profile photo upload by adding dedicated route and removing duplicate dashboard view)
                        
<<<<<<< .merge_file_JU6Y4l
                        {{-- Member Photo Container Slot --}}
                        <div class="shrink-0">
=======
                        {{-- Member Photo Container & Direct Upload Slot --}}
                        <div class="flex flex-col items-center shrink-0 space-y-3">
>>>>>>> .merge_file_ljzA8C
                            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-950 border-4 border-purple-500/40 shadow-inner flex items-center justify-center">
                                @if(isset($profile) && $profile->profile_image)
                                    <img src="{{ asset('storage/' . $profile->profile_image) }}" 
                                         alt="Profile Photo" 
                                         class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl select-none">📸</span>
                                @endif
                            </div>

                            {{-- Direct Photo Upload Form --}}
                            @if(isset($profile))
                                <form action="{{ route('member.update-photo') }}" method="POST" enctype="multipart/form-data" class="flex flex-col items-center">
                                    @csrf
                                    @method('PUT')
                                    
                                    <input type="file" name="profile_image" id="portal_profile_image" class="hidden" accept="image/jpeg,image/png,image/jpg" onchange="this.form.submit()">
                                    
                                    <label for="portal_profile_image" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-700 shadow-sm text-xs font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition cursor-pointer">
                                        📷 Update Photo
                                    </label>
                                </form>
                            @endif
                        </div>

                        {{-- Member Info Grid --}}
                        <div class="flex-1 w-full text-center md:text-left">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $profile->full_name ?? $user->name }}</h3>
                                    <p class="text-sm font-medium text-purple-600 dark:text-purple-400 mt-0.5">Registered Chapter Member</p>
                                </div>
                                
                                {{-- Real-Time Current Membership Status Badge --}}
                                <div>
                                    @if(isset($membership))
                                        <span class="inline-flex flex-col items-center md:items-end">
                                            <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Fiscal Status ({{ $membership->membership_year }})</span>
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide shadow-sm
                                                {{ Str::contains($membership->status, ['Active', 'Paid']) ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20' : 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20' }}">
                                                <span class="w-2 h-2 rounded-full mr-2 {{ Str::contains($membership->status, ['Active', 'Paid']) ? 'bg-emerald-500 dark:bg-emerald-400 animate-pulse' : 'bg-amber-500 dark:bg-amber-400' }}"></span>
                                                {{ $membership->status }}
                                            </span>
                                        </span>
                                    @else
<<<<<<< .merge_file_JU6Y4l
 
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-800/30 shadow-sm">

                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20 shadow-sm">
 
=======
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-800/30 shadow-sm">

                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-rose-50 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20 shadow-sm">
 (Update layout theme switching and support for light/dark mode across dashboard and dentist directory)
>>>>>>> .merge_file_ljzA8C
                                            No Active Record Logged
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <hr class="my-6 border-gray-200 dark:border-gray-800">

                            {{-- Detailed Metadata Rows --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm text-left">
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">PRC License Number</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200 mt-0.5 block">{{ $profile->prc_no ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact Number</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200 mt-0.5 block">{{ $profile->contact_no ?? 'N/A' }}</span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email Address</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200 mt-0.5 block">{{ $user->email }}</span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Clinic Address</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-300 mt-0.5 block bg-gray-50 dark:bg-gray-950/60 p-3 rounded-xl border border-gray-200 dark:border-gray-800/60 leading-relaxed">
                                        {{ $profile->clinic_address ?? 'No clinic address registered.' }}
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>