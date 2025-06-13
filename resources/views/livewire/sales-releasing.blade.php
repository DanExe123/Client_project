<div x-cloak class="grid grid-cols-1 lg:grid-cols-1 gap-4 w-full md:w-full mx-auto" x-data="SalesTable()">
    <!-- LEFT SIDE: Supplier Master Table (2/3 width) -->
    <div class="lg:col-span-2 space-y-1">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-900">Sales Releasing</h2>

        <!-- Tabs -->
        <div class="flex flex-wrap gap-2 mb-2 pt-2">
            <x-button rounded="lg" light teal icon="user" label="DR" @click="filterByStatus('DR')"
                :class="currentTab === 'DR' ? 'bg-blue-600' : 'bg-gray-300'" class="" />

            <x-button rounded="lg" light teal icon="user" label="Invoice" @click="filterByStatus('Invoice')"
                :class="currentTab === 'Invoice' ? 'bg-green-600' : 'bg-gray-300'" class="" />

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
        </div>

        <div x-show="currentTab === 'Invoice'" x-cloak>
            <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full">
                <table class="min-w-xl w-full border-collapse bg-white text-left text-sm text-gray-500">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-4">
                                <input type="checkbox" class="h-4 w-4 text-green-600" />
                            </th>
                            <th class="px-6 py-4 font-medium text-gray-900">PO #</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Customer</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Status</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Total</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        @foreach ($invoiceOrders as $poItem)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <input type="checkbox" value="{{ $poItem->id }}" class="h-4 w-4 text-green-600" />
                                </td>
                                <td class="px-6 py-4">{{ $poItem->po_number }}</td>
                                <td class="px-6 py-4">{{ $poItem->customer->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $poItem->order_date?->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">{{ $poItem->status }}</td>
                                <td class="px-6 py-4">₱{{ number_format($poItem->total_amount, 2) }}</td>
                                <td class="px-6 py-4 space-x-2 flex flex-wrap">
                                    <a href="{{ route('serve-sale-releasing', ['id' => $poItem->id]) }}">
                                        <x-button rounded="lg" light blue label="Serve" />
                                    </a>
                                    <a href="{{ route('reprint-invoice', ['id' => $poItem->id]) }}">
                                        <x-button rounded="lg" light yellow label="Reprint Invoice" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div x-show="currentTab === 'DR'" x-cloak>
            <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full">
                <table class="min-w-xl w-full border-collapse bg-white text-left text-sm text-gray-500">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-4">
                                <input type="checkbox" class="h-4 w-4 text-blue-600" />
                            </th>
                            <th class="px-6 py-4 font-medium text-gray-900">PO #</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Customer</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Status</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Total</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        @foreach ($drOrders as $poItem)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <input type="checkbox" value="{{ $poItem->id }}" class="h-4 w-4 text-blue-600" />
                                </td>
                                <td class="px-6 py-4">{{ $poItem->po_number }}</td>
                                <td class="px-6 py-4">{{ $poItem->customer->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $poItem->order_date?->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">{{ $poItem->status }}</td>
                                <td class="px-6 py-4">₱{{ number_format($poItem->total_amount, 2) }}</td>
                                <td class="px-6 py-4 space-x-2 flex flex-wrap">
                                    <a href="{{ route('serve-sale-releasing', ['id' => $poItem->id]) }}">
                                        <x-button rounded="lg" light blue label="Serve" />
                                    </a>
                                    <a href="{{ route('reprint-invoice', ['id' => $poItem->id]) }}">
                                        <x-button rounded="lg" light yellow label="Reprint Invoice" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div x-show="currentTab === 'All'" x-cloak>
            <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full">
                <table class="min-w-xl w-full border-collapse bg-white text-left text-sm text-gray-500">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-4">
                                <input type="checkbox" class="h-4 w-4 text-purple-600" />
                            </th>
                            <th class="px-6 py-4 font-medium text-gray-900">PO #</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Customer</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Type</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Status</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Total</th>
                            <th class="px-6 py-4 font-medium text-gray-900">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        @foreach ($allOrders as $poItem)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <input type="checkbox" value="{{ $poItem->id }}" class="h-4 w-4 text-purple-600" />
                                </td>
                                <td class="px-6 py-4">{{ $poItem->po_number }}</td>
                                <td class="px-6 py-4">{{ $poItem->customer->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4">{{ $poItem->order_date?->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">{{ $poItem->receipt_type }}</td>
                                <td class="px-6 py-4">{{ $poItem->status }}</td>
                                <td class="px-6 py-4">₱{{ number_format($poItem->total_amount, 2) }}</td>
                                <td class="px-6 py-4 space-x-2 flex flex-wrap">
                                    <x-button rounded="lg" light blue label="Serve" wire:click="serve({{ $poItem->id }})" />
                                    <x-button rounded="lg" light yellow label="Reprint Invoice"
                                        wire:click="reprintInvoice({{ $poItem->id }})" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function SalesTable() {
            return {
                search: '',
                selected: [],
                currentTab: 'DR',
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