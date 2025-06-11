<div>
    <div>
        <div x-data="supplierPOApp()" class="space-y-1">
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">Recieving</h2>
            <!-- Tab Buttons -->
            <div class="flex flex-wrap gap-2 pt-2 mb-2">
                <x-button rounded="lg" light teal  icon="check-circle"  label="For Approval"  @click="filterByStatus('For Approval')" 
                    :class="currentTab === 'For Approval' ? 'bg-blue-600' : 'bg-gray-300'" 
                    class=""/>
            
                    <x-button rounded="lg" light green icon="user" label="Approved" @click="filterByStatus('Approved')" 
                    :class="currentTab === 'Approved' ? 'bg-green-600' : 'bg-gray-300'" 
                    class=""/>
                    
                    <x-button rounded="lg" light red icon="user" label="Cancelled" @click="filterByStatus('Cancelled')" 
                    :class="currentTab === 'Cancelled' ? 'bg-green-600' : 'bg-gray-300'" 
                    class=""/>
            </div>
           

            <!-- Action Buttons -->
            <div class="flex gap-2 justify-end">
                <!-- search barr -->

                <div class="w-full sm:max-w-xs flex justify-start relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
                    </span>
                    <input
                        type="text"
                        x-model="search"
                        placeholder="Search..."
                        class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                </div>
                
                <a href="{{ route('recievingapproval') }}">
                    <x-button emerald right-icon="check-circle" label="Approve" />
                </a>
                
                <x-button right-icon="pencil" interaction="positive"
                    x-bind:class="selected.length === 0 ?
                        'bg-gray-300 text-white cursor-not-allowed' :
                        'bg-[#12ffac] hover:bg-[#13eda1] text-white'"
                    x-bind:disabled="selected.length === 0" x-on:click="$openModal('Edit')">
                </x-button>
           
            </div>
    
      
            <div class="overflow-auto rounded-lg border border-gray-200 shadow-md">
                <table class="min-w-[800px] w-full border-collapse bg-white text-left text-sm text-gray-500">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-4">
                                <input type="checkbox" @change="toggleAll" :checked="isAllSelected"
                                    class="h-4 w-4 text-blue-600" />
                            </th>
                            <th class="px-6 py-4 font-medium text-gray-900">PO #</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Customer</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Transaction</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Status</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Total</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        <template x-for="poItem in filteredPO" :key="poItem.id">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <input type="checkbox" :value="poItem.id" x-model="selected"
                                        class="h-4 w-4 text-blue-600" />
                                </td>
                                <td class="px-6 py-4" x-text="poItem.PO"></td>
                                <td class="px-6 py-4" x-text="poItem.Customer"></td>
                                <td class="px-6 py-4" x-text="poItem.Date"></td>
                                <td class="px-6 py-4" x-text="poItem.Transaction"></td>
                                <td class="px-6 py-4" x-text="poItem.Status"></td>
                                <td class="px-6 py-4" x-text="poItem.Total"></td>
                                <td class="px-6 py-4" x-text="poItem.Action"></td>
                                <td class="px-6 py-4">
                                    <x-button 
                                        outline 
                                        primary 
                                        label="View" 
                                        @click="viewPO(poItem.id)" 
                                    />
                                </td>
                                
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    
        <!-- Alpine.js Script -->
        <script>
            function supplierPOApp() {
                return {
                    currentTab: 'For Approval',
                    selected: [],
                    po: [{
                            id: 1,
                            PO: 'PO001',
                            Customer: 'Customer A',
                            Date: '2025-06-01',
                            Transaction: 'DR',
                            Status: 'For Approval',
                            Total: '$1,000'
                        },
                        {
                            id: 2,
                            PO: 'PO002',
                            Customer: 'Customer B',
                            Date: '2025-06-02',
                            Transaction: 'Invoice',
                            Status: 'Approved',
                            Total: '$2,500'
                        },
                        {
                            id: 3,
                            PO: 'PO003',
                            Customer: 'Customer C',
                            Date: '2025-06-03',
                            Transaction: 'Invoice',
                            Status: 'Cancelled',
                            Total: '$500'
                        },
                        {
                            id: 4,
                            PO: 'PO004',
                            Customer: 'Customer D',
                            Date: '2025-06-04',
                            Transaction: 'Invoice',
                            Status: 'For Approval',
                            Total: '$1,750'
                        },
                    ],
                    get filteredPO() {
                        return this.po.filter(poItem => poItem.Status === this.currentTab);
                    },
                    filterByStatus(status) {
                        this.currentTab = status;
                        this.selected = [];
                    },
                    get isAllSelected() {
                        return this.filteredPO.length > 0 && this.filteredPO.every(po => this.selected.includes(po.id));
                    },
                    toggleAll(event) {
                        if (event.target.checked) {
                            this.selected = this.filteredPO.map(po => po.id);
                        } else {
                            this.selected = [];
                        }
                    }
                };
            }
        </script>
    
    </div>
</div>
