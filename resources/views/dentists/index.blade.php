<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('PDA Dentist Directory') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data='{ 
        search: "",
        sortBy: "last_name",
        dentists: [
            @foreach($dentists as $dentist)
            {
                "id": "{{ $dentist->id }}",
                "name": {!! json_encode($dentist->full_name ?? "") !!},
                "lastName": {!! json_encode($dentist->last_name ?? $dentist->full_name ?? "") !!},
                "firstName": {!! json_encode($dentist->first_name ?? "") !!},
                "prc": "{{ $dentist->prc_no ?? "" }}",
                "contact": "{{ $dentist->contact_no ?? "" }}",
                "clinic": {!! json_encode($dentist->clinic_address ?? "") !!},
                "birthday": "{{ $dentist->birthdate ?? "" }}",
                "createdAt": "{{ $dentist->created_at ?? "" }}",
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
        get filteredDentists() {
            let filtered = this.dentists.filter(d => this.matches(d));

            return filtered.sort((a, b) => {
                if (this.sortBy === "last_name") {
                    return (a.lastName || "").localeCompare(b.lastName || "");
                } else if (this.sortBy === "first_name") {
                    return (a.firstName || "").localeCompare(b.firstName || "");
                } else if (this.sortBy === "latest") {
                    return new Date(b.createdAt) - new Date(a.createdAt);
                } else if (this.sortBy === "oldest") {
                    return new Date(a.createdAt) - new Date(b.createdAt);
                } else if (this.sortBy === "prc") {
                    return (a.prc || "").localeCompare(b.prc || "", undefined, {numeric: true});
                } else if (this.sortBy === "birthday") {
                    return new Date(a.birthday || "9999-12-31") - new Date(b.birthday || "9999-12-31");
                }
                return 0;
            });
        },
        get filteredCount() {
            return this.filteredDentists.length;
        }
    }'>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 overflow-hidden shadow-xl sm:rounded-2xl p-6 border border-gray-200 dark:border-purple-900/40 transition-colors duration-200">
                
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6 gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Registry Records</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Manage local PDA chapter rows and trace automated multi-year membership statuses.</p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-2.5">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.register.form') }}" 
                               class="inline-flex items-center px-3.5 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none transition">
                                <svg class="-ml-1 mr-1.5 h-4 w-4 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0zM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                                </svg>
                                Add Admin
                            </a>

                            <button onclick="document.getElementById('csvImportModal').classList.remove('hidden')"
                                    class="inline-flex items-center px-3.5 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none transition">
                                <svg class="-ml-1 mr-1.5 h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Import CSV
                            </button>
                        @endif

                        <a :href="'{{ route('dentists.export') }}' + (search ? '?search=' + encodeURIComponent(search) : '')" 
                           class="inline-flex items-center px-3.5 py-2 border border-gray-300 dark:border-gray-700 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none transition">
                            <svg class="-ml-1 mr-1.5 h-4 w-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Export CSV
                        </a>

                        <a href="{{ route('dentists.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#5045EB] dark:bg-purple-600 hover:bg-[#3c37d2] dark:hover:bg-purple-700 focus:outline-none transition">
                            + Register Dentist
                        </a>
                    </div>
                </div>

                {{-- Statistics Widgets Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gray-50 dark:bg-gray-800/80 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Directory</p>
                            <h4 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['total_dentists'] }}</h4>
                        </div>
                    </div>
                    <div class="bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Active This Year</p>
                            <h4 class="text-2xl font-bold text-emerald-900 dark:text-emerald-300 mt-1">{{ $stats['active_members'] }}</h4>
                        </div>
                    </div>
                    <div class="bg-amber-50/50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/30 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Pending Logs</p>
                            <h4 class="text-2xl font-bold text-amber-900 dark:text-amber-300 mt-1">{{ $stats['pending_members'] }}</h4>
                        </div>
                    </div>
                    <div class="bg-rose-50/50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Inactive/Delinquent</p>
                            <h4 class="text-2xl font-bold text-rose-900 dark:text-rose-300 mt-1">{{ $stats['inactive_members'] }}</h4>
                        </div>
                    </div>
                </div>

                {{-- Search and Sort Controls Bar --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                    <div class="w-full sm:w-1/3">
                        <input type="text" 
                               x-model.debounce.300ms="search" 
                               placeholder="Search by name or PRC number..." 
                               class="w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div class="w-full sm:w-auto">
                        <select x-model="sortBy" 
                                class="w-full sm:w-auto rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm px-4 py-2">
                            <option value="last_name">Sort by: Last Name</option>
                            <option value="first_name">Sort by: First Name</option>
                            <option value="latest">Sort by: Latest Member</option>
                            <option value="oldest">Sort by: Oldest Member</option>
                            <option value="prc">Sort by: PRC Number</option>
                            <option value="birthday">Sort by: Birthday</option>
                        </select>
                    </div>
                </div>

                {{-- Table Roster View --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">PRC Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact No.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Clinic Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Membership Years Logged</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                            <template x-for="dentist in filteredDentists" :key="dentist.id">
                                <tr x-show="matches(dentist)" x-transition:enter="transition ease-out duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-gray-100" x-text="dentist.name"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="dentist.prc"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="dentist.contact"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" x-text="dentist.clinic"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <template x-for="membership in dentist.memberships">
                                            <span :class="membership.status && (membership.status.toUpperCase().includes('LM') || membership.status.toLowerCase().includes('lifetime')) 
                                                  ? 'bg-purple-200 text-purple-900 dark:bg-purple-900/60 dark:text-purple-200 border border-purple-400 dark:border-purple-700 font-bold shadow-sm' 
                                                  : (membership.status && (membership.status.includes('Active') || membership.status.includes('Paid')) 
                                                      ? 'bg-green-100 text-green-800 dark:bg-green-950/40 dark:text-green-300 border dark:border-green-800/30' 
                                                      : 'bg-yellow-100 text-yellow-800 dark:bg-amber-950/40 dark:text-amber-300 border dark:border-amber-800/30')"
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-1 mb-1 group border border-transparent">
                                                
                                                <!-- Crown Icon for Lifetime Members -->
                                                <template x-if="membership.status && (membership.status.toUpperCase().includes('LM') || membership.status.toLowerCase().includes('lifetime'))">
                                                    <span class="mr-1">👑</span>
                                                </template>

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
                                    <td colspan="6" class="px-6 py-10 text-center text-sm font-medium text-gray-400 dark:text-gray-500 bg-gray-50/50 dark:bg-gray-800/20">
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

    <!-- CSV Import Modal Window -->
    <div id="csvImportModal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900 bg-opacity-60 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-lg max-w-md w-full p-6 shadow-xl text-gray-900 dark:text-gray-100">
            <h3 class="text-lg font-medium mb-2">Import Dentist Roster (CSV)</h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Upload a CSV file containing columns like: <code>full_name, email_address, prc_no, contact_no, home_address, clinic_address, membership_year, payment_status</code>.</p>
            
            <form action="{{ route('dentists.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <input type="file" name="csv_file" accept=".csv, .txt" required class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-gray-800 file:text-indigo-700 dark:file:text-indigo-400 hover:file:bg-indigo-100">
                    @error('csv_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="document.getElementById('csvImportModal').classList.add('hidden')" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-sm rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-sm rounded-md hover:bg-purple-700">Upload & Import</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>