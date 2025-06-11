<div class="grid grid-cols-1 lg:grid-cols-1 gap-4 w-full md:w-full mx-auto " x-data="POTable()">
    <!-- LEFT SIDE: Supplier Master Table (2/3 width) -->
    <div class="lg:col-span-2 space-y-1">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-900">Customer PO</h2>

        <!-- Tabs -->
        <div class="flex flex-wrap gap-2 mb-2 pt-2">
            <x-button rounded="lg" light teal icon="user" label="DR" @click="filterByStatus('DR')"
                :class="currentTab === 'DR' ? 'bg-blue-600' : 'bg-gray-300'" class="" />

            <x-button rounded="lg" light teal icon="user" label="Invoice" @click="filterByStatus('Invoice')"
                :class="currentTab === 'Invoice' ? 'bg-green-600' : 'bg-gray-300'" class="" />

            <x-button rounded="lg" light teal icon="user" label="Costumerpo" @click="filterByStatus('Costumerpo')"
                :class="currentTab === 'Costumerpo' ? 'bg-green-600' : 'bg-gray-300'" class="" />
        </div>
        <!-- end tabs -->

        <!-- Search and Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Bar -->
            <div class="w-full sm:max-w-xs flex justify-start relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
                </span>
                <input type="text" x-model="search" placeholder="Search..."
                    class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
            </div>

            <!-- Button Group -->
            <div class="flex gap-2">
                <x-button emerald right-icon="plus" x-on:click="$openModal('Add')" />


                <x-button right-icon="pencil" interaction="positive" x-bind:class="selected.length === 0 ?
                        'bg-gray-300 text-white cursor-not-allowed' :
                        'bg-[#12ffac] hover:bg-[#13eda1] text-white'" x-bind:disabled="selected.length === 0"
                    x-on:click="$openModal('Edit')">
                </x-button>


                <x-button right-icon="trash" interaction="negative" x-bind:class="selected.length === 0 ?
                        'bg-red-300 text-white cursor-not-allowed' :
                        'bg-red-600 hover:bg-red-700 text-white'" x-bind:disabled="selected.length === 0"
                    x-on:click="$openModal('Delete')">
                </x-button>

            </div>
        </div>

        <!-- Customer Table -->
        <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full">
            <table class="min-w-xl w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-4">
                            <input type="checkbox" @change="toggleAll" :checked="isAllSelected"
                                class="h-4 w-4 text-blue-600" />
                        </th>
                        <th class="px-6 py-4 font-medium text-gray-900">PO #</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Customer</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Status</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    <template x-for="poItem in po" :key="poItem . id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <input type="checkbox" :value="poItem . id" x-model="selected"
                                    class="h-4 w-4 text-blue-600" />
                            </td>
                            <td class="px-6 py-4" x-text="poItem.PO"></td>
                            <td class="px-6 py-4" x-text="poItem.Customer"></td>
                            <td class="px-6 py-4" x-text="poItem.Date"></td>
                            <td class="px-6 py-4" x-text="poItem.Status"></td>
                            <td class="px-6 py-4" x-text="poItem.Total"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- RIGHT SIDE: Add PO Form (1/3 width) -->
    <div class="col-span-1 w-full md:w-full bg-white rounded-lg border shadow-md p-5 space-y-4 mt-5 mx-auto ml-1">
        <h3 class="text-lg font-bold text-gray-800">
            Csutomer PO <span class="text-blue-500"></span>
        </h3>

        <!-- User and Address Inputs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-select label="Select Transaction type" placeholder="Select some user" option-label="name"
                option-value="id" />
            <x-select label="Select Customer" placeholder="Select some customer" option-label="name"
                option-value="id" />

            <div x-data="{
                currentDate: '', // Property to hold the date string in YYYY-MM-DD format
                init() {
                    // Get today's date
                    const today = new Date();
                    const year = today.getFullYear();
                    const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                    const day = String(today.getDate()).padStart(2, '0');
            
                    // Format it as YYYY-MM-DD
                    this.currentDate = `${year}-${month}-${day}`;
                }
            }">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" id="date" name="date"
                    class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
                    x-model="currentDate">

            </div>
        </div>

        <!-- Product Table -->
        <h4 class="text-md font-semibold text-gray-700">Products</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 font-medium">Product Description</th>
                        <th class="border px-2 py-1 font-medium">Qty</th>
                        <th class="border px-2 py-1 font-medium">Unit Price</th>
                        <th class="border px-2 py-1 font-medium">Discount%</th>
                        <th class="border px-2 py-1 font-medium">Subtotal</th>
                        <th class="border px-2 py-1 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-2 py-2">
                            <select
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
                                <option>Select</option>
                                <option>Dog Food</option>
                                <option>Cat Shampoo</option>
                                <option>Vaccine A</option>
                            </select>
                        </td>
                        <td class="border px-2 py-2">
                            <input type="number" value="1" min="1"
                                class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" />
                        </td>
                        <td class="border px-2 py-2">
                            <input type="number" value="0.00" step="0.01"
                                class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" />
                        </td>
                        <td class="border px-2 py-2">
                            <input type="text" value="0.00" readonly
                                class="w-full bg-gray-100 border-gray-300 rounded-md px-2 py-1 text-sm" />
                        </td>
                        <td class="border px-2 py-2 text-center">0.00</td>
                        <td class="border px-2 py-2 text-center">
                            <x-button red label="Remove" class="px-2 py-1 text-xs h-8" />
                        </td>
                    </tr>
                </tbody>
                <template x-for="(item, index) in products" :key="index">
                    <tr class="hover:bg-gray-50">
                        <td class="border px-2 py-2">
                            <input type="text" x-model="item.barcode"
                                class="w-full border-gray-300 rounded-md px-2 py-1 text-sm"
                                placeholder="Scan or enter barcode" />
                        </td>
                        <td class="border px-2 py-2">
                            <select
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
                                <option>Select</option>
                                <option>Dog Food</option>
                                <option>Cat Shampoo</option>
                                <option>Vaccine A</option>
                            </select>
                        </td>
                        <td class="border px-2 py-2">
                            <input type="number" x-model="item.quantity" @input="updateTotal(index)" min="1"
                                class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" />
                        </td>
                        <td class="border px-2 py-2">
                            <input type="number" x-model="item.price" @input="updateTotal(index)" step="0.01"
                                class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" />
                        </td>
                        <td class="border px-2 py-2">
                            <input type="text" :value="item . total . toFixed(2)" readonly
                                class="w-full bg-gray-100 border-gray-300 rounded-md px-2 py-1 text-sm" />
                        </td>
                        <td class="border px-2 py-2 text-center">
                            <x-button red label="Remove" class="px-2 py-1 text-xs h-8"
                                x-on:click="removeProduct(index)" />
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>

            <!-- Add Product Button -->
            <div class="pt-2 ml-2">
                <x-button green label="Add Product" x-on:click="addProduct()" />
            </div>

            <!-- Remarks Section -->
            <div class="pt-4">
                <x-textarea name="remarks" label="Remarks" placeholder="Write your remarks" />
                <div class="flex justify-end pt-2">
                    <x-button blue label="Submit" />
                </div>
            </div>
        </div>
    </div>

    </tr>
    </tfoot>
</div>
</div>


<script>
    function POTable() {
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
            products: [],
            addProduct() {
                this.products.push({ barcode: '', product_id: '', quantity: 1, price: 0.00, total: 0.00 });
            },
            removeProduct(index) {
                this.products.splice(index, 1);
            },
            updateTotal(index) {
                const item = this.products[index];
                item.total = (parseFloat(item.quantity) || 0) * (parseFloat(item.price) || 0);
            },
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