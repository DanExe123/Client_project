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
            <div>
                <label class="block text-sm text-gray-700 mb-1">Receipt Type</label>
                <input type="text" :value="po.receipt_type" disabled
                    class="rounded-md border py-2 px-3 bg-gray-100 text-sm" placeholder="Transaction Type" />
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Customer Name</label>
                <input type="text" :value="po.customer_name" disabled
                    class="rounded-md border py-2 px-3 bg-gray-100 text-sm" placeholder="Customer" />
            </div>
            <div>
                <label class="block text-sm text-gray-700 mb-1">Date</label>
                <input type="date" :value="po.order_date" disabled
                    class="rounded-md border py-2 px-3 bg-gray-100 text-sm" />
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Discount</label>
                <input type="number" :value="po.purchase_discount" disabled
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
                                <div class="relative">
                                    <input x-model.number="item.quantity" @input="updateTotal(index)" type="number"
                                        min="1" class="w-full border bg-gray-100 outline-none text-right"
                                        :class="{ 'ring-2 ring-red-400': item.error }" />
                                    <p class="text-xs text-gray-500 italic mt-1">
                                        Max: <span x-text="item.available_quantity"></span>
                                    </p>
                                    <p x-show="item.error" class="text-xs text-red-500 italic mt-1">
                                        Quantity exceeds Purchase Order!
                                    </p>
                                </div>
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

                <!-- Total Row -->
                <tfoot>
                    <tr class="bg-gray-50 font-semibold text-gray-800">
                        <td colspan="4" class="border px-2 py-2 text-right">Total:</td>
                        <td class="border px-2 py-2 text-right" x-text="subtotal.toFixed(2)"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @if ($errors->has('quantity'))
            <div x-data="{ errorMessage: @js($errors->first('quantity')) }"
                class="mb-4 p-3 bg-red-100 text-red-700 rounded-md border border-red-300">
                <span x-text="errorMessage"></span>
            </div>
        @endif

        <!-- Remarks and Serve Button -->
        <div class="pt-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea rows="3" disabled x-text="po.remarks"
                class="w-full rounded-md border py-2 px-3 bg-gray-100 shadow-sm sm:text-sm"></textarea>

            <div class="flex justify-end pt-4">
                <form method="POST" action="{{ route('sales-releasing.serve', ['id' => $po->id]) }}" x-ref="serveForm">
                    @csrf

                    <!-- Hidden field to submit products as JSON -->
                    <input type="hidden" name="products" :value="JSON.stringify(products)" />

                    <x-button type="submit" blue label="Serve" />
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function POTable(poData) {
        const products = poData.items.map(item => ({
            product_id: item.product_id,
            product_barcode: item.product_barcode,
            product_description: item.product_description || 'N/A',
            available_quantity: item.quantity, // from DB
            quantity: item.quantity, // user input
            price: parseFloat(item.product?.selling_price || 0),
            discount: parseFloat(item.product_discount),
            total: 0,
            error: false // track validation
        }));
        // Auto-calculate totals on load
        products.forEach((item, index) => {
            const subtotal = item.quantity * item.price;
            const discountAmount = subtotal * (item.discount / 100);
            item.total = subtotal - discountAmount;
        });


        return {
            po: {
                receipt_type: poData.receipt_type || 'N/A',
                customer_name: poData.customer?.name || 'N/A',
                order_date: poData.order_date?.substring(0, 10) || '',
                purchase_discount: poData.purchase_discount,
                remarks: poData.remarks
            },
            products,

            updateTotal(index) {
                const item = this.products[index];

                if (!item.quantity || item.quantity <= 0) {
                    item.total = 0;
                    item.error = false;
                    return;
                }

                item.error = item.quantity > item.available_quantity;

                const subtotal = item.quantity * item.price;
                const discountAmount = subtotal * (item.discount / 100);
                item.total = subtotal - discountAmount;
            },

            get subtotal() {
                return this.products.reduce((sum, item) => sum + item.total, 0);
            },
            get total() {
                const discount = this.po.purchase_discount || 0;
                return this.subtotal - (this.subtotal * (discount / 100));
            }
        };
    }

</script>