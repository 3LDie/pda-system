<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-white leading-tight">
            {{ __('PDA Dentist Members') }}

        </h2>
    </x-slot>

    <!-- ✅ Fixed: Bypasses Eloquent relationship calls by querying pda_memberships directly via profile_id -->
    <div class="py-12" x-data='{ 
        search: "",
        dentists: [
            @foreach($dentists as $dentist)
            {
                "id": "{{ $dentist->id }}",
                "name": {!! json_encode($dentist->full_name ?? "") !!},
                "image": "{{ $dentist->profile_image ? asset("storage/" . $dentist->profile_image) : "" }}",
                "prc": "{{ $dentist->prc_no ?? "" }}",
                "contact": "{{ $dentist->contact_no ?? "" }}",
                "clinic": {!! json_encode($dentist->clinic_address ?? "") !!},
                "memberships": [
                    @if($dentist->profile_id)
                        @foreach(DB::table("pda_memberships")->where("dentist_profile_id", $dentist->profile_id)->orderBy("membership_year", "desc")->take(2)->get() as $membership)
                        { 
                            "id": "{{ $membership->id }}",
                            "year": "{{ $membership->membership_year ?? "N/A" }}", 
                            "status": {!! json_encode($membership->status ?? "No Status") !!}
                        },
                        @endforeach
                    @endif
                ]
            },
            @endforeach
        ],
        matches(dentist) {
            if (!this.search) return true;
            const term = this.search.toLowerCase();
            
            const nameMatch = dentist.name ? dentist.name.toLowerCase().includes(term) : false;
            const prcMatch = dentist.prc ? dentist.prc.toLowerCase().includes(term) : false;
            
            return nameMatch || prcMatch;
        },
        get filteredCount() {
            return this.dentists.filter(d => this.matches(d)).length;
        }
    }'>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-700 overflow-hidden shadow-sm sm:rounded-lg p-6 transition-colors duration-200">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-gray-100 dark:border-gray-600 pb-4 mb-6 gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Membership Records</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Manage local PDA chapter rows and trace automated multi-year membership statuses.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition duration-150 ease-in-out">
                                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0zM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                </svg>
                                Add System Admin
                            </a>
                        @endif

                        <a :href="'{{ route('dentists.export') }}' + (search ? '?search=' + encodeURIComponent(search) : '')"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition">
                            Export Roster (CSV)
                        </a>
                        <a href="{{ route('dentists.create') }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#5045EB] dark:bg-gray-600 focus:outline-none transition">
                            Register New Dentist
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-600 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Members</p>
                            <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['total_dentists'] }}</h4>
                        </div>
                    </div>
                    <div class="bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Active</p>
                            <h4 class="text-2xl font-bold text-emerald-900 dark:text-emerald-300 mt-1">{{ $stats['active_members'] }}</h4>
                        </div>
                    </div>
                    <div class="bg-amber-50/50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/30 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Status Logs</p>
                            <h4 class="text-2xl font-bold text-amber-900 dark:text-amber-300 mt-1">{{ $stats['pending_members'] }}</h4>
                        </div>
                    </div>
                    <div class="bg-rose-50/50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Inactive Members</p>
                            <h4 class="text-2xl font-bold text-rose-900 dark:text-rose-300 mt-1">{{ $stats['inactive_members'] }}</h4>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <input type="text"
                           x-model.debounce.300ms="search"
                           placeholder="Search by name or PRC number..."
                           class="w-full md:w-1/3 rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">Photo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">PRC Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact No.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Clinic Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Membership Years Logged</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200 dark:divide-gray-600">
                            <template x-for="dentist in dentists">
                                <tr x-show="matches(dentist)" x-transition:enter="transition ease-out duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-600 shadow-sm flex items-center justify-center">
                                            <template x-if="dentist.image">
                                                <img :src="dentist.image" 
                                                     alt="Profile" 
                                                     class="w-full h-full object-cover" 
                                                     x-on:error="dentist.image = ''">
                                            </template>
                                            <template x-if="!dentist.image">
                                                <span class="text-gray-400 font-sans text-xs select-none">📸</span>
                                            </template>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100" x-text="dentist.name"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="dentist.prc"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="dentist.contact"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="dentist.clinic"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <template x-for="membership in dentist.memberships">
                                            <span :class="membership.status && (membership.status.includes('Active') || membership.status.includes('Paid')) ? 'bg-green-100 text-green-800 dark:bg-green-950/40 dark:text-green-300 border dark:border-green-800/30' : 'bg-yellow-100 text-yellow-800 dark:bg-amber-950/40 dark:text-amber-300 border dark:border-amber-800/30'"
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-1 mb-1 group border border-transparent">
                                                <span x-text="membership.year + ' — ' + membership.status"></span>
                                                
                                                @if(auth()->user()->role === 'admin')
                                                <form :action="'/memberships/' + membership.id" method="POST" class="inline flex items-center mb-0 ml-1.5" onsubmit="return confirm('Are you sure you want to completely delete this specific membership log year item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition font-bold font-sans text-xs focus:outline-none cursor-pointer">
                                                        &times;
                                                    </button>
                                                </form>
                                                @endif
                                            </span>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                        <a :href="`/dentists/${dentist.id}/renew`" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition font-semibold bg-green-50 dark:bg-green-950/30 px-3 py-1.5 rounded-md mr-2 inline-flex items-center">🔄 Renew</a>
                                        <a :href="`/dentists/${dentist.id}/edit`" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition font-semibold bg-indigo-50 dark:bg-indigo-950/30 px-3 py-1.5 rounded-md inline-flex items-center">✏️ Edit</a>
                                    </td>
                                </tr>
                            </template>

                            <template x-if="filteredCount === 0">
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-50/50 dark:bg-gray-800/50">
                                        No registered dentists match your current search constraints.
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>