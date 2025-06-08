<div>
    <div x-data="creditDebitTable()" class="p-4 space-y-6">

        <!-- Nav Tabs -->
        <div class="flex space-x-2">
            <button
                :class="currentTab === 'Credit' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                class="px-4 py-2 rounded-md text-sm font-semibold"
                @click="filterByTab('Credit')">
                Credit
            </button>
            <button
                :class="currentTab === 'Debit' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'"
                class="px-4 py-2 rounded-md text-sm font-semibold"
                @click="filterByTab('Debit')">
                Debit
            </button>
        </div>

        <!-- Search and Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <!-- Search -->
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

            <!-- Button Group -->
            <div class="flex gap-2">
                <x-button emerald right-icon="plus" @click="$openModal('Add')" />

                <x-modal-card title="Add Credit" name="Add">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <!-- Left Column -->
                        <div class="space-y-4">
                            <!-- Select Date -->
                            <x-input label="Select Date" type="date" :value="now()->toDateString()" />
                
                            <!-- Select Customer -->
                            <x-select label="Select Customer" placeholder="Choose Customer">
                                <option value="">Customer A</option>
                                <option value="">Customer B</option>
                                <option value="">Customer C</option>
                            </x-select>
                
                            <!-- Select Receivable Doc (DR/Invoice) -->
                            <x-select label="Select Receivable Doc (DR/INVOICE)" placeholder="Choose Invoice/DR">
                                <option value="">DR-001</option>
                                <option value="">INV-202</option>
                                <option value="">DR-005</option>
                            </x-select>
                
                            <!-- Select Return Slip -->
                            <x-select label="Select Return Slip" placeholder="Choose Return Slip">
                                <option value="">RS-101</option>
                                <option value="">RS-102</option>
                                <option value="">RS-103</option>
                            </x-select>
                
                            <!-- Remarks -->
                            <x-textarea label="Remarks (Optional)" placeholder="Add any notes here..." />
                        </div>
                
                        <!-- Right Column -->
                        <div class="space-y-4">
                            <!-- Invoice Amount (auto-populated) -->
                            <x-input label="Invoice Amount" type="number" placeholder="₱0.00" readonly />
                
                            <!-- Sales Return Amount (auto-populated) -->
                            <x-input label="Sales Return Amount" type="number" placeholder="₱0.00" readonly />
                
                            <!-- Balance Amount -->
                            <x-input label="Balance Amount" type="number" placeholder="₱0.00" readonly />
                        </div>
                    </div>
                
                    <x-slot name="footer" class="flex justify-end gap-x-4">
                        <x-button flat label="Cancel" x-on:click="close" />
                        <x-button primary label="Save" wire:click="save" />
                    </x-slot>
                </x-modal-card>

                
                

                <x-button right-icon="pencil" interaction="positive"
                    x-bind:class="selected.length === 0 ? 'bg-gray-300 cursor-not-allowed text-white' : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'"
                    x-bind:disabled="selected.length === 0"
                    @click="$openModal('Edit')" />

                <x-button right-icon="minus" interaction="negative"
                    x-bind:class="selected.length === 0 ? 'bg-red-300 cursor-not-allowed text-white' : 'bg-red-600 hover:bg-red-700 text-white'"
                    x-bind:disabled="selected.length === 0"
                    @click="$openModal('Cancel')" />
            </div>

        </div>

        <!-- Table -->
        <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full">
            <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-4">
                            <input type="checkbox" @change="toggleAll" :checked="isAllSelected" class="h-4 w-4 text-blue-600" />
                        </th>
                        <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Customer Name</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Invoice/Dr #</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Return Slip</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Return Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    <template x-for="entry in filteredData" :key="entry.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <input type="checkbox" :value="entry.id" x-model="selected" class="h-4 w-4 text-blue-600" />
                            </td>
                            <td class="px-6 py-4" x-text="entry.date"></td>
                            <td class="px-6 py-4" x-text="entry.customer"></td>
                            <td class="px-6 py-4" x-text="entry.invoice"></td>
                            <td class="px-6 py-4" x-text="entry.returnSlip"></td>
                            <td class="px-6 py-4" x-text="entry.amount"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

    
    
    <script>
        function creditDebitTable() {
            return {
                search: '',
                selected: [],
                currentTab: 'Credit',
                entries: [
                    { id: 1, type: 'Credit', date: '2025-06-01', customer: 'John Doe', invoice: 'INV-001', returnSlip: 'RS-001', amount: '$100.00' },
                    { id: 2, type: 'Debit',  date: '2025-06-02', customer: 'Jane Smith', invoice: 'DR-002', returnSlip: 'RS-002', amount: '$150.00' },
                    { id: 3, type: 'Credit', date: '2025-06-03', customer: 'Michael Tan', invoice: 'INV-003', returnSlip: 'RS-003', amount: '$200.00' },
                    { id: 4, type: 'Debit',  date: '2025-06-04', customer: 'Lisa Wong', invoice: 'DR-004', returnSlip: 'RS-004', amount: '$250.00' },
                ],
                get filteredData() {
                    return this.entries.filter(entry =>
                        entry.type === this.currentTab &&
                        (entry.customer.toLowerCase().includes(this.search.toLowerCase()) ||
                         entry.invoice.toLowerCase().includes(this.search.toLowerCase()))
                    );
                },
                filterByTab(tab) {
                    this.currentTab = tab;
                    this.selected = [];
                },
                toggleAll(event) {
                    if (event.target.checked) {
                        this.selected = this.filteredData.map(e => e.id);
                    } else {
                        this.selected = [];
                    }
                },
                get isAllSelected() {
                    return this.filteredData.length > 0 && this.selected.length === this.filteredData.length;
                },
            };
        }
        </script>
        

