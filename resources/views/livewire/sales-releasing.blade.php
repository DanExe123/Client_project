<div x-cloak class="grid grid-cols-1 lg:grid-cols-1 gap-4 w-full md:w-full mx-auto"  x-data="SalesTable()">
    <!-- LEFT SIDE: Supplier Master Table (2/3 width) -->
    <div class="lg:col-span-2 space-y-1">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-900">Sales Releasing</h2>

                    <!-- Tabs -->
        <div class="flex flex-wrap gap-2 mb-2 pt-2">
            <x-button rounded="lg" light teal  icon="user" label="DR"  @click="filterByStatus('DR')" 
                :class="currentTab === 'DR' ? 'bg-blue-600' : 'bg-gray-300'" 
                class=""/>
        
                <x-button rounded="lg" light teal icon="user" label="Invoice" @click="filterByStatus('Invoice')" 
                :class="currentTab === 'Invoice' ? 'bg-green-600' : 'bg-gray-300'" 
                class=""/>
            
        </div>
        <!-- end tabs -->

        <!-- Search and Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Bar -->
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
        </div>

        <template x-if="currentTab === 'Invoice'">
            <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full">
              <table class="min-w-xl w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50 sticky top-0 z-10">
                  <tr>
                    <th class="px-4 py-4">
                      <input type="checkbox" @change="toggleAll" :checked="isAllSelected" class="h-4 w-4 text-green-600" />
                    </th>
                    <th class="px-6 py-4 font-medium text-gray-900">PO #</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Customer</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Status</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Total</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                  <template x-for="poItem in po" :key="poItem.id">
                    <tr class="hover:bg-gray-50">
                      <td class="px-4 py-4">
                        <input type="checkbox" :value="poItem.id" x-model="selected" class="h-4 w-4 text-green-600" />
                      </td>
                      <td class="px-6 py-4" x-text="poItem.PO"></td>
                      <td class="px-6 py-4" x-text="poItem.Customer"></td>
                      <td class="px-6 py-4" x-text="poItem.Date"></td>
                      <td class="px-6 py-4" x-text="poItem.Status"></td>
                      <td class="px-6 py-4" x-text="poItem.Total"></td>
                      <td class="px-6 py-4 space-x-2 flex flex-wrap">
                        <x-button rounded="lg" light blue label="serve" @click="serve(poItem.id)" />
                        <x-button rounded="lg" light yellow label="Reprint Invoice"  @click="reprintInvoice(poItem.id)" />
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </template>
        
          <template x-if="currentTab === 'DR'">
            <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full">
              <table class="min-w-xl w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50 sticky top-0 z-10">
                  <tr>
                    <th class="px-4 py-4">
                      <input type="checkbox" @change="toggleAll" :checked="isAllSelected" class="h-4 w-4 text-blue-600" />
                    </th>
                    <th class="px-6 py-4 font-medium text-gray-900">PO #</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Customer</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Status</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Total</th>
                    <th class="px-6 py-4 font-medium text-gray-900">Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                  <template x-for="poItem in po" :key="poItem.id">
                    <tr class="hover:bg-gray-50">
                      <td class="px-4 py-4">
                        <input type="checkbox" :value="poItem.id" x-model="selected" class="h-4 w-4 text-blue-600" />
                      </td>
                      <td class="px-6 py-4" x-text="poItem.PO"></td>
                      <td class="px-6 py-4" x-text="poItem.Customer"></td>
                      <td class="px-6 py-4" x-text="poItem.Date"></td>
                      <td class="px-6 py-4" x-text="poItem.Status"></td>
                      <td class="px-6 py-4" x-text="poItem.Total"></td>
                      <td class="px-6 py-4 space-x-2">
                        <x-button rounded="lg" light blue label="serve" @click="serve(poItem.id)" />
                        <x-button rounded="lg" light yellow label="Reprint Invoice"  @click="reprintInvoice(poItem.id)" />
                      </td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </template>

    </div>

    



<script>
    function SalesTable() {
        return {
            search: '',
            selected: [],
            currentTab: 'DR',
            allPOs: [  // Unfiltered full list
                { id: 1, PO: 'PO-1001', Customer: 'ABC Corp.', Date: '2025-05-01', Status: 'DR', Total: '$1,500' },
                { id: 2, PO: 'PO-1002', Customer: 'XYZ Ltd.', Date: '2025-05-03', Status: 'Invoice', Total: '$2,200' },
                { id: 3, PO: 'PO-1003', Customer: 'Acme Inc.', Date: '2025-05-05', Status: 'Costumerpo', Total: '$800' },
                { id: 4, PO: 'PO-1004', Customer: 'Another Client', Date: '2025-05-06', Status: 'DR', Total: '$1,000' },
                { id: 5, PO: 'PO-1005', Customer: 'Client Five', Date: '2025-05-07', Status: 'Invoice', Total: '$3,000' }
            ],
            get po() {
                // Filter by current tab (status) and search term
                return this.allPOs.filter(poItem =>
                    poItem.Status === this.currentTab &&
                    (poItem.PO.toLowerCase().includes(this.search.toLowerCase()) ||
                     poItem.Customer.toLowerCase().includes(this.search.toLowerCase()))
                );
            },
            toggleAll(event) {
                if (event.target.checked) {
                    this.selected = this.po.map(po => po.id);
                } else {
                    this.selected = [];
                }
            },
            get isAllSelected() {
                return this.po.length > 0 && this.selected.length === this.po.length;
            },
            filterByStatus(status) {
                this.currentTab = status;
                this.selected = []; // Optional: clear selection on tab switch
            },
        }
    }
</script>
