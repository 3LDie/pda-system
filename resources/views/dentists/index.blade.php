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
                name: '{{ addslashes($dentist->full_name) }}',
                prc: '{{ $dentist->prc_no }}',
                contact: '{{ $dentist->contact_no }}',
                clinic: '{{ addslashes($dentist->clinic_address) }}',
                memberships: [
                    @foreach($dentist->memberships as $membership)
                    { year: '{{ $membership->membership_year }}', status: '{{ $membership->status }}' },
                    @endforeach
                ]
            },
            @endforeach
        ],
        // Helper function to check if the dentist matches the search terms
        matches(dentist) {
            if (!this.search) return true;
            const term = this.search.toLowerCase();
            return dentist.name.toLowerCase().includes(term) || dentist.prc.includes(term);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
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
                                            <span :class="membership.status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mr-1">
                                                <span x-text="membership.year + ' (' + membership.status + ')'"></span>
                                            </span>
                                        </template>
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