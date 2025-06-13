<div x-data="POTable({{ json_encode($po) }})" class="p-4">
    <div class="bg-white border rounded-lg shadow-md p-5 space-y-4 mt-5 mx-auto">

        <!-- Breadcrumb -->
        <div class="text-gray-500 flex gap-3">
            <a class="font-medium" href="#">Sales Releasing</a>
            <span>&gt;</span>
            <span class="font-medium">Serve</span>
        </div>
        <hr>

        <!-- PO Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <input type="text" :value="po.receipt_type" disabled class="rounded-md border py-2 px-3 bg-gray-100 text-sm"
                placeholder="Transaction Type" />

            <input type="text" :value="po.customer_name" disabled
                class="rounded-md border py-2 px-3 bg-gray-100 text-sm" placeholder="Customer" />

            <div>
                <label class="block text-sm text-gray-700 mb-1">Date</label>
                <input type="date" :value="po.order_date" disabled
                    class="rounded-md border py-2 px-3 bg-gray-100 text-sm" />
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Discount</label>
                <input type="number" :value="po.discount" disabled
                    class="rounded-md border py-2 px-3 bg-gray-100 text-sm" />
            </div>
        </div>

        <!-- Products Table -->
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
                                <input x-model="item.product_description" type="text" disabled
                                    class="w-full border-none bg-gray-100 outline-none" />
                            </td>
                            <td class="border px-2 py-2">
                                <input x-model.number="item.quantity" @input="updateTotal(index)" type="number"
                                    class="w-full border-none outline-none text-right" />
                            </td>
                            <td class="border px-2 py-2">
                                <input x-model.number="item.price" type="number" disabled
                                    class="w-full border-none bg-gray-100 outline-none text-right" />
                            </td>
                            <td class="border px-2 py-2">
                                <input x-model.number="item.discount" type="number" disabled
                                    class="w-full border-none bg-gray-100 outline-none text-right" />
                            </td>
                            <td class="border px-2 py-2 text-right" x-text="item.total.toFixed(2)"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Remarks and Serve Button -->
        <div class="pt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea rows="3" disabled x-text="po.remarks"
                class="w-full rounded-md border py-2 px-3 bg-gray-100 shadow-sm sm:text-sm"></textarea>

            <div class="flex justify-end pt-4">
                <form method="POST" action="{{ route('sales-releasing.serve', ['id' => $po->id]) }}">
                    @csrf
                    <x-button type="submit" blue label="Serve" />
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function POTable(poData) {
        const products = poData.items.map(item => ({
            product_description: item.product_description || 'N/A',
            quantity: item.quantity,
            price: parseFloat(item.unit_price),
            discount: parseFloat(item.product_discount),
            total: parseFloat(item.subtotal) || 0
        }));

        return {
            po: {
                receipt_type: poData.receipt_type || 'N/A',
                customer_name: poData.customer?.name || 'N/A',
                order_date: poData.order_date?.substring(0, 10) || '',
                discount: poData.discount,
                remarks: poData.remarks
            },
            products,

            updateTotal(index) {
                const item = this.products[index];
                const subtotal = item.quantity * item.price;
                const discountAmount = subtotal * (item.discount / 100);
                item.total = subtotal - discountAmount;
            }
        };

    }
</script>