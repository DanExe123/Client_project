<div x-data="POTable()" class="p-4">
    <div class="col-span-1 w-full bg-white rounded-lg border shadow-md p-5 space-y-4 mt-5 mx-auto ml-1">

        <!-- Breadcrumb -->
        <div class="text-gray-500 flex text-start gap-3">
            <a class="text-gray-500 font-medium" href="#">Sales Releasing</a>
            <span>&gt;</span>
            <span class="text-gray-500 font-medium">Serve</span>
        </div> 
        <hr>

        <!-- Inputs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <select class="rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                <option>Select Transaction type</option>
                <option>Sale</option>
                <option>Return</option>
            </select>

            <select class="rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                <option>Select Customer</option>
                <option>Juan Dela Cruz</option>
                <option>Jane Smith</option>
            </select>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date"
                    class="block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    x-bind:value="new Date().toISOString().split('T')[0]" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Discount</label>
                <input type="number"
                    class="block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
            </div>
        </div>

        <h4 class="text-md font-semibold text-gray-700 pt-4">Products</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 font-medium">Product Description</th>
                        <th class="border px-2 py-1 font-medium">Qty</th>
                        <th class="border px-2 py-1 font-medium">Unit Price</th>
                        <th class="border px-2 py-1 font-medium">Discount%</th>
                        <th class="border px-2 py-1 font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(item, index) in products" :key="index">
                        <tr class="hover:bg-gray-50">
                            <td class="border px-2 py-2">
                                <input x-model="item.description" type="text" class="w-full border-none outline-none" placeholder="Product..." />
                            </td>
                            <td class="border px-2 py-2">
                                <input x-model.number="item.quantity" @input="updateTotal(index)" type="number" class="w-full border-none outline-none text-right" />
                            </td>
                            <td class="border px-2 py-2">
                                <input x-model.number="item.price" @input="updateTotal(index)" type="number" class="w-full border-none outline-none text-right" />
                            </td>
                            <td class="border px-2 py-2">
                                <input x-model.number="item.discount" @input="updateTotal(index)" type="number" class="w-full border-none outline-none text-right" />
                            </td>
                            <td class="border px-2 py-2 text-right" x-text="item.total.toFixed(2)"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Add Product Button -->
        <div class="pt-2 ml-2">
            <button type="button" x-on:click="addProduct()"
                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                Add Product
            </button>
        </div>

        <!-- Remarks and Submit -->
        <div class="pt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea rows="3"
                class="w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                placeholder="Write your remarks..."></textarea>

            <div class="flex justify-end pt-4">
                <a href="{{ route('serve-print-preview')}}">
                <x-button type="submit" blue label="Serve" />
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function POTable() {
        return {
            products: [
                {
                    description: 'Sample Product',
                    quantity: 1,
                    price: 100.00,
                    discount: 0,
                    total: 100.00,
                }
            ],
            addProduct() {
                this.products.push({
                    description: '',
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
