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
                        year: '{{ $membership->membership_year ?? 'N/A' }}', 
                        status: '{{ addslashes($membership->payment_status ?? $membership->status ?? 'No Status') }}' 
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
                    <div>
                        <a href="{{ route('dentists.create') }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition">
                           ➕ Register New Dentist
                        </a>
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
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-1">
                                                <span x-text="membership.year + ' (' + membership.status + ')'"></span>
                                            </span>
                                        </template>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">
                                        <a :href="'/dentists/' + dentist.id + '/renew'" 
                                           class="text-green-600 hover:text-green-900 transition font-semibold bg-green-50 px-3 py-1.5 rounded-md mr-2">
                                            🔄 Renew
                                        </a>
                                        <a :href="'/dentists/' + dentist.id + '/edit'" 
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