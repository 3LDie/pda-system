<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('PDA Dentist Directory') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        search: '',
        dentists: [
            @foreach($dentists as $dentist)
            {
                id: '{{ $dentist->id }}',
                name: '{{ addslashes($dentist->full_name) }}',
                prc: '{{ $dentist->prc_no }}',
                contact: '{{ $dentist->contact_no }}',
                clinic: '{{ addslashes($dentist->clinic_address) }}',
                memberships: [
                    @foreach($dentist->memberships as $membership)
                    { 
                        id: '{{ $membership->id }}',
                        year: '{{ $membership->membership_year ?? 'N/A' }}', 
                        status: '{{ addslashes($membership->status ?? 'No Status') }}' 
                    },
                    @endforeach
                ]
            },
            @endforeach
        ],
        matches(dentist) {
            if (!this.search) return true;
            const term = this.search.toLowerCase();
            return dentist.name.toLowerCase().includes(term) || dentist.prc.includes(term);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b border-gray-100 pb-4 mb-6 gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Registry Records</h3>
                        <p class="text-sm text-gray-500">Manage local PDA chapter rows and trace automated multi-year membership statuses.</p>
                    </div>
                    <div class="flex items-center space-x-3 gap-2">
                        <a :href="'{{ route('dentists.export') }}' + (search ? '?search=' + encodeURIComponent(search) : '')" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none transition">
                            📥 Export Roster (CSV)
                        </a>
                        <a href="{{ route('dentists.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition">
                            ➕ Register New Dentist
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gray-50 border border-gray-100 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Directory</p>
                            <h4 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_dentists'] }}</h4>
                        </div>
                        <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center text-xl">👥</div>
                    </div>

                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Active This Year</p>
                            <h4 class="text-2xl font-bold text-emerald-900 mt-1">{{ $stats['active_members'] }}</h4>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-xl">✅</div>
                    </div>

                    <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Pending Logs</p>
                            <h4 class="text-2xl font-bold text-amber-900 mt-1">{{ $stats['pending_members'] }}</h4>
                        </div>
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center text-xl">⏳</div>
                    </div>

                    <div class="bg-rose-50/50 border border-rose-100 rounded-xl p-5 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Inactive/Delinquent</p>
                            <h4 class="text-2xl font-bold text-rose-900 mt-1">{{ $stats['inactive_members'] }}</h4>
                        </div>
                        <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center text-xl">🚨</div>
                    </div>
                </div>

                <div class="mb-6">
                    <input type="text" 
                           x-model="search" 
                           placeholder="Search by name or PRC number..." 
                           class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PRC Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact No.</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clinic Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membership Years Logged</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="dentist in dentists">
                                <tr x-show="matches(dentist)" x-transition:enter="transition ease-out duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900" x-text="dentist.name"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="dentist.prc"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="dentist.contact"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="dentist.clinic"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <template x-for="membership in dentist.memberships">
                                            <span :class="membership.status && (membership.status.includes('Active') || membership.status.includes('Paid')) ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-1 mb-1 group">
                                                <span x-text="membership.year + ' (' + membership.status + ')'"></span>
                                                
                                                <form :action="'/memberships/' + membership.id" method="POST" class="inline flex items-center mb-0 ml-1.5" onsubmit="return confirm('Are you sure you want to completely delete this specific membership log year item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition font-bold font-sans text-xs focus:outline-none cursor-pointer">
                                                        &times;
                                                    </button>
                                                </form>
                                            </span>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                        <a :href="`/dentists/${dentist.id}/renew`" 
                                           class="text-green-600 hover:text-green-900 transition font-semibold bg-green-50 px-3 py-1.5 rounded-md mr-2">
                                            🔄 Renew
                                        </a>
                                        <a :href="`/dentists/${dentist.id}/edit`" 
                                           class="text-indigo-600 hover:text-indigo-900 transition font-semibold bg-indigo-50 px-3 py-1.5 rounded-md">
                                            ✏️ Edit
                                        </a>
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