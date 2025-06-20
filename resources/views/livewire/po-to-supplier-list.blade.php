<div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-5 w-full" x-data="POTable()">
        <!-- LEFT SIDE: Supplier Master Table (2/3 width) -->
        <div class="lg:col-span-2 space-y-1">
            <!-- Title -->
            <h2 class="text-2xl font-semibold text-gray-900 mb-2">PO to Supplier list</h2>


            <!-- Search and Buttons -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <!-- Search Bar -->
                <div class="w-full sm:max-w-xs flex justify-start relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
                    </span>
                    <input type="text" x-model="search" placeholder="Search..."
                        class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                    <input type="text" x-model="search" placeholder="Search..."
                        class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                </div>
                <!-- Button Group -->
                <div class="flex gap-2">
                    <x-button emerald right-icon="plus" x-on:click="$openModal('Add')" />
                    @include('partials.supplier-modal.supplier-master-create')

                    <x-button right-icon="pencil" interaction="positive" x-bind:class="selected.length === 0 ?

                    <x-button right-icon=" pencil" interaction="positive" x-bind:class="selected.length === 0 ?
                            'bg-gray-300 text-white cursor-not-allowed' :
                            'bg-[#12ffac] hover:bg-[#13eda1] text-white'" x-bind:disabled="selected.length === 0"
                        x-on:click="$openModal('Edit')">
                        'bg-[#12ffac] hover:bg-[#13eda1] text-white'" x-bind:disabled="selected.length === 0"
                        x-on:click="$openModal('Edit')">
                    </x-button>
                    @include('partials.supplier-modal.supplier-edit')

                    <x-button right-icon="trash" interaction="negative" x-bind:class="selected.length === 0 ?

                    <x-button right-icon=" trash" interaction="negative" x-bind:class="selected.length === 0 ?
                            'bg-red-300 text-white cursor-not-allowed' :
                            'bg-red-600 hover:bg-red-700 text-white'" x-bind:disabled="selected.length === 0"
                        x-on:click="$openModal('Delete')">
                        'bg-red-600 hover:bg-red-700 text-white'" x-bind:disabled="selected.length === 0"
                        x-on:click="$openModal('Delete')">
                    </x-button>
                    @include('partials.supplier-modal.supplier-delete')
                </div>
            </div>
            <!-- Supplier Table -->
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
                            <th class="px-6 py-4 font-medium text-gray-900">Status</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        <template x-for="poItem in po" :key="poItem . id">
                            <template x-for="poItem in po" :key="poItem . id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" :value="poItem . id" x-model="selected" <input
                                            type="checkbox" :value="poItem . id" x-model="selected"
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
        <div class="col-span-1 w-full md:w-[550px] bg-white rounded-lg border shadow-md p-5 space-y-4">
            <h3 class="text-lg font-bold text-gray-800">
                Add <span class="text-blue-500">PO</span> to <span class="text-blue-500">Supplier</span>
            </h3>

            <!-- User and Address Inputs -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-select label="Search a User" placeholder="Select some user" option-label="name" option-value="id" />
                <x-input label="Customer Address" placeholder="Enter Address" />
            </div>

            <div 
            x-data="{
                date: new Date(),
                init() {
                    this.setDefaultDateTime();
                },
                setDefaultDateTime() {
                    const pad = num => String(num).padStart(2, '0');
                    const yyyy = this.date.getFullYear();
                    const mm = pad(this.date.getMonth() + 1);
                    const dd = pad(this.date.getDate());
                    const hh = pad(this.date.getHours());
                    const min = pad(this.date.getMinutes());
                    this.datetime = `${yyyy}-${mm}-${dd}T${hh}:${min}`;
                },
                datetime: ''
            }"
            >
            <label for="datetime">Select Date & Time:</label>
            <input type="datetime-local" x-model="datetime" id="datetime" class="border p-2 rounded" />
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
                            <td class="border px-1 py-2 text-center">
                                <x-button red label="Remove" class="px-2 py-1 text-xs h-8" />
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="border px-2 py-2 text-right font-semibold">Total:</td>
                            <td class="border px-2 py-2">
                </table>
            </div>
            <!-- Add Product Button -->
            <div class="pt-2">
                <x-button green label="Add Product" />
            </div>
            <hr>
            <!-- Remarks Section -->
            <div class="pt-4">
                <x-textarea name="remarks" label="remarks" placeholder="Write your remarks" />
                <div class="flex justify-end pt-2">
                    <x-button blue label="Submit" />
                </div>
            </div>
            </tr>
            </tfoot>

        </div>
    </div>

</div>
</div>
</div>