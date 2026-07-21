<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $role === 'admin' ? __('Admin Dashboard') : __('My Member Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if($role === 'admin')
                {{-- Admin View Landing State --}}
                <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100 dark:border-gray-600">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Welcome Back, Administrator!</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Use the navigation bar above to manage Chapter rows or provision administrative system accounts.</p>
                </div>
            @else
                {{-- 🔒 Non-Admin (Member) Portal Layout --}}
                <div class="bg-white dark:bg-[#26262A] dark:text-slate-100 overflow-hidden shadow-xl sm:rounded-xl border border-gray-100 dark:border-[#26262A] p-8">
                    <div class="flex flex-col md:flex-row items-center gap-8">
                        
                        {{-- Member Photo Container Slot --}}
                        <div class="shrink-0">
                            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-800 border-4 border-gray-100 dark:border-gray-600 shadow-md flex items-center justify-center">
                                @if(isset($profile) && $profile->profile_image)
                                    <img src="{{ asset('storage/' . $profile->profile_image) }}" 
                                         alt="Profile Photo" 
                                         class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl select-none">📸</span>
                                @endif
                            </div>
                        </div>

                        {{-- Member Info Grid --}}
                        <div class="flex-1 w-full text-center md:text-left">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $profile->full_name ?? $user->name }}</h3>
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mt-0.5">Registered Chapter Member</p>
                                </div>
                                
                                {{-- Real-Time Current Membership Status Badge --}}
                                <div>
                                    @if(isset($membership))
                                        <span class="inline-flex flex-col items-center md:items-end">
                                            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-1">Fiscal Status ({{ $membership->membership_year }})</span>
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide shadow-sm
                                                {{ Str::contains($membership->status, ['Active', 'Paid']) ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800/30' : 'bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/30' }}">
                                                <span class="w-2 h-2 rounded-full mr-2 {{ Str::contains($membership->status, ['Active', 'Paid']) ? 'bg-green-500' : 'bg-amber-500' }}"></span>
                                                {{ $membership->status }}
                                            </span>
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-rose-100 dark:bg-rose-900/30 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-805/30 shadow-sm">
                                            No Active Record Logged
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <hr class="my-6 border-gray-100 dark:border-gray-700">

                            {{-- Detailed Metadata Rows --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm text-left">
                                <div>
                                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">PRC License Number</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200 mt-0.5 block">{{ $profile->prc_no ?? 'N/A' }}</span>
                                </div>
                                <div>
                                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Contact Number</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200 mt-0.5 block">{{ $profile->contact_no ?? 'N/A' }}</span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Email Address</span>
                                    <span class="font-medium text-gray-800 dark:text-gray-200 mt-0.5 block">{{ $user->email }}</span>
                                </div>
                                <div class="sm:col-span-2">
                                    <span class="block text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Clinic Address</span>
                                    <span class="font-medium text-gray-700 dark:text-gray-300 mt-0.5 block bg-gray-50 dark:bg-gray-800 p-3 rounded-md border border-gray-100 dark:border-gray-600 leading-relaxed">
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