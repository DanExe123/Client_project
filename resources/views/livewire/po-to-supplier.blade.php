<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 w-full md:w-[1400px] " x-data="POTable()">
    <!-- LEFT SIDE: Supplier Master Table (2/3 width) -->
    <div class="lg:col-span-2 space-y-1">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-900 mb-2">PO TO SUPPLIER</h2>

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
                {{-- @include('partials.supplier-modal.supplier-master-create') --}}

                <x-button right-icon="pencil" interaction="positive" x-bind:class="selected.length === 0 ?
                        'bg-gray-300 text-white cursor-not-allowed' :
                        'bg-[#12ffac] hover:bg-[#13eda1] text-white'" x-bind:disabled="selected.length === 0"
                    x-on:click="$openModal('Edit')">
                </x-button>
                {{-- @include('partials.supplier-modal.supplier-edit') --}}

                <x-button right-icon="trash" interaction="negative" x-bind:class="selected.length === 0 ?
                        'bg-red-300 text-white cursor-not-allowed' :
                        'bg-red-600 hover:bg-red-700 text-white'" x-bind:disabled="selected.length === 0"
                    x-on:click="$openModal('Delete')">
                </x-button>
                {{-- @include('partials.supplier-modal.supplier-delete') --}}
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
    <div class="col-span-1 w-full md:w-[700px] bg-white rounded-lg border shadow-md p-5 space-y-4">
        <h3 class="text-lg font-bold text-gray-800">
            Add <span class="text-blue-500">PO</span> to <span class="text-blue-500">Supplier</span>
        </h3>

        <!-- User and Address Inputs -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="supplier" class="block text-sm font-medium text-gray-700 mb-1">Select Supplier</label>
                <select id="supplier" name="supplier"
                    class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                    <option value="" disabled selected>Select a supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="document-type" class="block text-sm font-medium text-gray-700 mb-1">Select Type</label>
                <select id="document-type" name="document-type"
                    class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm">
                    <option value="" disabled selected>Select type</option>
                    <option value="DR">DR</option>
                    <option value="INVOICE">INVOICE</option>
                </select>
            </div>

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
                        <th class="border px-2 py-1 font-medium">Barcode</th>
                        <th class="border px-2 py-1 font-medium">Product Description</th>
                        <th class="border px-2 py-1 font-medium">Qty</th>
                        <th class="border px-2 py-1 font-medium">Unit Price</th>
                        <th class="border px-2 py-1 font-medium">Subtotal</th>
                        <th class="border px-2 py-1 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <!-- Barcode input -->
                        <td class="border px-2 py-2">
                            <input type="text" placeholder="Scan or enter barcode"
                                class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" />
                        </td>

                        <!-- Product select dropdown -->
                        <td class="border px-2 py-2">
                            <select class="w-full border-gray-300 rounded-md px-2 py-1 text-sm">
                                <option>Select</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->description }}</option>
                                @endforeach
                            </select>
                        </td>

                        <!-- Quantity input -->
                        <td class="border px-2 py-2">
                            <input type="number" value="1" min="1"
                                class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" />
                        </td>

                        <!-- Price input -->
                        <td class="border px-2 py-2">
                            <input type="number" value="0.00" step="0.01"
                                class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" />
                        </td>

                        <!-- Total (readonly) -->
                        <td class="border px-2 py-2">
                            <input type="text" value="0.00" readonly
                                class="w-full bg-gray-100 border-gray-300 rounded-md px-2 py-1 text-sm" />
                        </td>

                        <!-- Remove button -->
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