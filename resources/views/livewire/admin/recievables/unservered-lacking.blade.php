<div>
    <div x-cloak x-data="purchaseOrderUI" class="space-y-4">
        <!-- Title and Buttons -->
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="text-2xl font-semibold text-gray-900">Unserved Purchase Orders</h2>
    
            <div class="flex gap-2">
                <button
                    @click="downloadPDF"
                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 text-sm"
                >
                    Download PDF
                </button>
                <button
                    @click="window.print()"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm"
                >
                    Print
                </button>
            </div>
        </div>
    
        <!-- Search Bar -->
        <div class="w-full sm:max-w-xs relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
            </span>
            <input
                type="text"
                x-model="search"
                placeholder="Search by customer or product..."
                class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            />
        </div>
    
        <!-- Table -->
        <div class="overflow-auto rounded-lg border border-gray-200">
            <table class="min-w-[1000px] w-full border-collapse bg-white text-left text-sm text-gray-700">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-4 font-medium text-gray-900">Date</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Customer Name</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Product PO</th>
                        <th class="px-4 py-4 font-medium text-gray-900">PO Quantity</th>
                        <th class="px-4 py-4 font-medium text-gray-900">PO Served Quantity</th>
                        <th class="px-4 py-4 font-medium text-gray-900">PO Quantity Difference</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    <template x-for="(order, index) in filteredOrders" :key="index">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4" x-text="order.date"></td>
                            <td class="px-4 py-4" x-text="order.customer"></td>
                            <td class="px-4 py-4" x-text="order.product"></td>
                            <td class="px-4 py-4" x-text="order.quantity"></td>
                            <td class="px-4 py-4" x-text="order.served"></td>
                            <td class="px-4 py-4" x-text="order.quantity - order.served"></td>
                        </tr>
                    </template>
                    <tr x-show="filteredOrders.length === 0">
                        <td colspan="6" class="text-center p-4 text-gray-500">No records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('purchaseOrderUI', () => ({
            search: '',
            orders: [
                { date: '2025-06-01', customer: 'John Doe', product: 'Dog Food', quantity: 100, served: 80 },
                { date: '2025-06-02', customer: 'Jane Smith', product: 'Cat Litter', quantity: 50, served: 50 },
                { date: '2025-06-03', customer: 'Pet Lovers Inc.', product: 'Fish Flakes', quantity: 75, served: 60 },
            ],
            get filteredOrders() {
                const term = this.search.toLowerCase();
                return this.orders.filter(o =>
                    o.customer.toLowerCase().includes(term) || o.product.toLowerCase().includes(term)
                );
            },
            downloadPDF() {
                const link = document.createElement('a');
                const today = new Date().toISOString().split('T')[0];
                link.href = '/path/to/your/pdf/files/' + today + '_Purchase_Order_Unserved.pdf'; // Update this path
                link.download = today + '_Purchase_Order_Unserved.pdf';
                link.click();
            }
        }));
    });
    </script>
    
</div>
