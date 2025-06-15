<div>
    <div x-cloak x-data="purchaseOrderUI" class="space-y-4">
        <!-- Title and Buttons -->
        <div class="flex justify-between items-center flex-wrap gap-2">
            <h2 class="text-2xl font-semibold text-gray-900">Unserved Purchase Orders</h2>

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
                        <th class="px-4 py-4 font-medium text-gray-900">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @forelse ($unservedData as $row)
                    <tr>
                        <td class="px-6 py-4">{{ $row['date'] }}</td>
                        <td class="px-6 py-4">{{ $row['customer_name'] }}</td>
                        <td class="px-6 py-4">{{ $row['product_description'] }}</td>
                        <td class="px-6 py-4">{{ $row['po_quantity'] }}</td>
                        <td class="px-6 py-4">{{ $row['served_quantity'] }}</td>
                        <td class="px-6 py-4 text-red-600 font-semibold">{{ $row['difference'] }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('print-unservered') }}" target="_blank"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Print</a>
                         
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">No unserved lacking found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
</div>
