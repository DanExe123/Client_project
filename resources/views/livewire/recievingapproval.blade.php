<div x-data="POTable()">
    <div class="col-span-1 w-full bg-white rounded-lg border shadow-md p-5 space-y-4 mt-5 mx-auto ml-1">
        <h3 class="text-lg font-bold text-gray-800">Recieving Approval</h3>

        <div class="text-gray-500 flex text-start gap-3">
            <a class="text-gray-500 font-medium" wire:navigate href="{{ route('recieving') }}">Recieving</a>
            <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
            <span class="text-gray-500 font-medium "> Recieving Approval</span>
        </div> 
        <hr>

        <!-- Inputs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-select label="Select Transaction type" placeholder="Select some user" option-label="name" option-value="id" />
            <x-select label="Select Customer" placeholder="Select some customer" option-label="name" option-value="id" />

            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" id="date" name="date"
                    class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    x-bind:value="new Date().toISOString().split('T')[0]" />
            </div>
            <div>
                <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Discount</label>
                <input type="number" id="discount" name="discount"
                    class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
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
                        <th class="border px-2 py-1 font-medium">Discount%</th> <!-- Added -->
                        <th class="border px-2 py-1 font-medium">Subtotal</th>
                        <th class="border px-2 py-1 font-medium">Action</th> <!-- Modified -->
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in products" :key="index">
                        <tr class="hover:bg-gray-50">
                            <td class="border px-2 py-2">
                                <input type="text" x-model="item.barcode"
                                    class="w-full border-gray-300 rounded-md px-2 py-1 text-sm"
                                    placeholder="Scan or enter barcode" />
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
                                <input type="number" x-model="item.discount" @input="updateTotal(index)" step="0.01" min="0" max="100"
                                    class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" />
                            </td>
                            <td class="border px-2 py-2">
                                <input type="text" :value="item.total.toFixed(2)" readonly
                                    class="w-full bg-gray-100 border-gray-300 rounded-md px-2 py-1 text-sm" />
                            </td>
                            <td class="border px-2 py-2 space-y-1 text-center">
                                <x-button red label="Remove" class="px-2 py-1 text-xs h-8 w-full" x-on:click="removeProduct(index)" />
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            

            <!-- Add Product Button -->
            <div class="pt-2 ml-2">
                <x-button green label="Add Product" x-on:click="addProduct()" />
            </div>

            <!-- Remarks and Submit -->
            <div class="pt-4">
                <x-textarea name="remarks" label="Remarks" placeholder="Write your remarks" />
                <div class="flex justify-end pt-2">
                    <x-button blue label="Approved" />
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  function POTable() {
    return {
        products: [],
        addProduct() {
    this.products.push({
        barcode: '',
        quantity: 1,
        price: 0.00,
        discount: 0,
        total: 0.00
    });
},
updateTotal(index) {
    const item = this.products[index];
    const qty = parseFloat(item.quantity) || 0;
    const price = parseFloat(item.price) || 0;
    const discount = parseFloat(item.discount) || 0;
    const subtotal = qty * price;
    const discountAmount = subtotal * (discount / 100);
    item.total = subtotal - discountAmount;
}

    };
}

</script>
