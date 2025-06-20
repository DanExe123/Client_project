<div>
    <div class="p-4 space-y-6">
        <!-- Nav Tabs -->
        <div class="flex space-x-2">
            <x-button
                rounded="lg"
                light
                teal
                icon="check-circle"
                label="Credit"
                @click="filterTab('Credit')"
                x-bind:class="currentTab === 'Credit' ? 'bg-blue-600' : 'bg-gray-300'"
            />
            <div x-data="{ showToast: false }" class="relative">
                <!-- Debit Button -->
                <x-button
                    rounded="lg"
                    light
                    green
                    icon="user"
                    label="Debit"
                    @click="showToast = true"
                    {{-- Removed x-bind:class based on showToast here, as it's not a tab --}}
                    class="bg-gray-300" {{-- Default class, or adjust as needed for an active tab state --}}
                />
                <!-- Toast -->
                <div
                    x-show="showToast"
                    x-transition
                    x-init="$watch('showToast', value => { if (value) setTimeout(() => showToast = false, 4000) })"
                    class="absolute top-0 right-0 left-15 mt-2 mr-2 z-50 bg-white border border-red-500 shadow-lg rounded-lg p-4 w-80"
                >
                    <div class="font-semibold text-red-500 mb-1">
                        <span class="text-red-500"> Debit Page Unavailable</span>
                    </div>
                    <div class="text-sm text-gray-700">
                        This Debit page is not available for now. <br>
                        Please contact the developer or go to <strong>Developer Settings</strong> and send your concern.
                    </div>
                </div>
            </div>
        </div>
        @if (session()->has('message'))
            @php
                $message = session('message');
                // The 'No data to save.' message isn't relevant anymore with the new validation.
                // You might adjust this based on your specific warning messages.
                $isWarning = str_contains($message, 'No data') || str_contains($message, 'cannot exceed') || str_contains($message, 'error');
            @endphp

            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition class="mt-2">
                <x-alert
                    :title="$message"
                    :icon="$isWarning ? 'x-circle' : 'check-circle'"
                    :color="$isWarning ? 'warning' : 'success'"
                    flat
                    :class="$isWarning ? '!bg-yellow-300' : '!bg-green-300'"
                    class="!w-full"
                />
            </div>
        @endif
        <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
            <!-- Customer Filter -->
            <div>
                <label class="text-sm text-gray-700 font-medium mb-1 block">Select Customer</label>
                <select wire:model.live="filterCustomer"
                    class="w-full rounded-md border-gray-300 px-3 py-2 shadow-sm text-sm">
                    <option value="">All</option>
                    @foreach ($customerOptions as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <!-- Summary Totals -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div class="bg-white rounded-lg shadow border p-4">
                <h4 class="text-sm font-medium text-gray-600">Total from Selected Releases</h4>
                <p class="text-xl font-bold text-blue-600">₱{{ number_format($totalSelectedReleased, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow border p-4">
                <h4 class="text-sm font-medium text-gray-600">Total from Selected Returns</h4>
                <p class="text-xl font-bold text-green-600">₱{{ number_format($totalSelectedReturns, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow border p-4">
                <h4 class="text-sm font-medium text-gray-600">Remaining Balance (Released - Returns)</h4>
                <p class="text-xl font-bold text-red-600">₱{{ number_format($totalSelectedReleased - $totalSelectedReturns, 2) }}</p>
            </div>
        </div>

        <!-- Released Items Selection Section -->
        <div class="overflow-auto rounded-lg border border-gray-300 shadow-sm w-full mt-4">
            <h3 class="text-lg font-semibold px-4 py-2 bg-gray-100">Select Released Items to Deduct From</h3>
            <table class="min-w-full bg-white text-left text-sm text-gray-600">
                <thead class="bg-gray-50 sticky top-0">
                    <tr>
                        <th class="px-4 py-3">Released Date</th>
                        <th class="px-4 py-3">Sales No.</th>
                        <th class="px-4 py-3">Amount</th>
                        <th class="px-4 py-3">Add to Applied</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($releasedItems as $released)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ \Carbon\Carbon::parse($released->release_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-3">Sales No.{{ $released->id }}</td>
                            <td class="px-4 py-3">₱{{ number_format($released->total_amount, 2) }}</td>
                            <td class="px-4 py-3">
                                @if(in_array($released->id, $selectedReleasedItemIds)) {{-- Changed to selectedReleasedItemIds --}}
                                    <button
                                        wire:click="removeReleasedItem({{ $released->id }})"
                                        class="px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition"
                                    >
                                        Remove
                                    </button>
                                @else
                                    <button
                                        wire:click="addReleasedItem({{ $released->id }})"
                                        class="px-3 py-1 text-sm bg-blue-500 text-white rounded hover:bg-blue-600 transition"
                                    >
                                        Add to Applied
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-400">No released items available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mt-4">
            <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 font-medium text-gray-900">Return Date</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Barcode</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Product Description</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Quantity</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Unit Price</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Subtotal</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Total Amount</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Action</th> {{-- Changed header to Action --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @if ($filterCustomer)
                        @forelse ($returnItemsPaginated as $item) {{-- Use $returnItemsPaginated here --}}
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->return->order_date)->format('Y-m-d') }}</td>
                                <td class="px-6 py-4">{{ $item->product_barcode }}</td>
                                <td class="px-6 py-4">{{ $item->product_description }}</td>
                                <td class="px-6 py-4">{{ $item->quantity }}</td>
                                <td class="px-6 py-4">₱{{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4">₱{{ number_format($item->subtotal, 2) }}</td>
                                <td class="px-6 py-4">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                <td class="text-center flex justify-center gap-2 my-2">
                                    @if(in_array($item->id, $selectedReturnItemIds)) {{-- Conditional buttons based on selection --}}
                                        <button
                                            wire:click.prevent="removeToSave({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="removeToSave({{ $item->id }})"
                                            class="!h-6 px-3 border rounded text-red-600 border-red-600 hover:bg-red-50"
                                            wire:key="remove-btn-{{ $item->id }}"
                                        >
                                            <span wire:loading.remove wire:target="removeToSave({{ $item->id }})">Remove from Save</span>
                                            <span wire:loading wire:target="removeToSave({{ $item->id }})">Removing...</span>
                                        </button>
                                    @else
                                        <button
                                            wire:click.prevent="addToSave({{ $item->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="addToSave({{ $item->id }})"
                                            class="!h-6 px-3 border rounded text-green-600 border-green-600 hover:bg-green-50"
                                            wire:key="add-btn-{{ $item->id }}"
                                        >
                                            <span wire:loading.remove wire:target="addToSave({{ $item->id }})">Add to Save</span>
                                            <span wire:loading wire:target="addToSave({{ $item->id }})">Loading...</span>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    No return items found for this customer.
                                </td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No data found. Please select a customer.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <div class="space-y-2 mb-2 px-2">
                {{ $returnItemsPaginated->links() }}
            </div>
        </div>
        <div class="mt-4 text-right">
            <button
                wire:click="saveCredit"
                wire:loading.attr="disabled"
                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50">
                Save Credit
            </button>
        </div>
    </div>
</div>