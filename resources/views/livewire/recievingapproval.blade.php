<div>
    <div class="col-span-1 w-full bg-white rounded-lg border shadow-md p-5 space-y-4 mt-5 mx-auto ml-1">
        <h3 class="text-lg font-bold text-gray-800">Recieving Approval</h3>

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="mt-2">
                <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                    class="!bg-green-300 !w-full" />
            </div>
        @endif

        <div class="text-gray-500 flex text-start gap-3">
            <a class="text-gray-500 font-medium" wire:navigate href="{{ route('recieving') }}">Recieving</a>
            <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
            <span class="text-gray-500 font-medium "> Recieving Approval</span>
        </div>
        <hr>

        <!-- Inputs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {{-- PO Number --}}
            <div>
                <label for="po_number" class="block text-sm font-medium text-gray-700 mb-1">PO Number</label>
                <input type="text" id="po_number" disabled
                    class="block w-full rounded-md border border-gray-300 bg-gray-100 py-2 px-3 shadow-sm sm:text-sm"
                    value="{{ $po_number }}" />
            </div>
            {{-- Supplier Name --}}
            <div>
                <label for="supplier_name" class="block text-sm font-medium text-gray-700 mb-1">Supplier Name</label>
                <input type="text" id="supplier_name" disabled
                    class="block w-full rounded-md border border-gray-300 bg-gray-100 py-2 px-3 shadow-sm sm:text-sm"
                    value="{{ $supplier_name }}" />
            </div>
            {{-- Receipt Type --}}
            <div>
                <label for="receipt_type" class="block text-sm font-medium text-gray-700 mb-1">Receipt Type</label>
                <input type="text" id="receipt_type" disabled
                    class="block w-full rounded-md border border-gray-300 bg-gray-100 py-2 px-3 shadow-sm sm:text-sm"
                    value="{{ $receipt_type }}" />
            </div>
            {{-- Order Date --}}
            <div>
                <label for="order_date" class="block text-sm font-medium text-gray-700 mb-1">Order Date</label>
                <input type="date" id="order_date" disabled
                    class="block w-full rounded-md border border-gray-300 bg-gray-100 py-2 px-3 shadow-sm sm:text-sm"
                    value="{{ \Carbon\Carbon::parse($order_date)->format('Y-m-d') }}" />
            </div>
            {{-- Purchase Discount --}}
            <div>
                <label for="purchase_discount" class="block text-sm font-medium text-gray-700 mb-1">Purchase
                    Discount</label>
                <input type="number" id="purchase_discount" disabled
                    class="block w-full rounded-md border border-gray-300 bg-gray-100 py-2 px-3 shadow-sm sm:text-sm"
                    value="{{ $purchase_discount }}" />
            </div>
        </div>

        <h4 class="text-md font-semibold text-gray-700">Products</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 font-medium">Barcode</th>
                        <th class="border px-2 py-1 font-medium">Product Description</th>
                        <th class="border px-2 py-1 font-medium">Qty</th>
                        <th class="border px-2 py-1 font-medium">Unit Price</th>
                        <th class="border px-2 py-1 font-medium">Discount%</th>
                        <th class="border px-2 py-1 font-medium">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-2 py-2">{{ $item['barcode'] }}</td>
                            <td class="border px-2 py-2">{{ $item['description'] }}</td>
                            <td class="border px-2 py-2">
                                <input type="number" min="0" step="1" wire:model.lazy="items.{{ $index }}.quantity"
                                    wire:input="updateSubtotal({{ $index }})"
                                    class="w-full border rounded px-2 py-1 text-sm" />
                            </td>
                            <td class="border px-2 py-2">
                                <input type="number" min="0" step="0.01" wire:model.lazy="items.{{ $index }}.unit_price"
                                    wire:input="updateSubtotal({{ $index }})"
                                    class="w-full border rounded px-2 py-1 text-sm" />
                            </td>
                            <td class="border px-2 py-2">{{ $item['discount'] }}%</td>
                            <td class="border px-2 py-2">{{ number_format($item['subtotal'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>

                {{-- Total Footer --}}
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="border px-2 py-2 text-right font-semibold">Total:</td>
                        <td class="border px-2 py-2 font-semibold text-right" colspan="2">
                            <div wire:loading wire:target="updateSubtotal"
                                class="flex justify-end items-center space-x-2">
                                <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span class="text-gray-500 text-sm italic">Calculating...</span>
                            </div>

                            <div wire:loading.remove wire:target="updateSubtotal">
                                {{ number_format($grandTotal, 2) }}
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- Remarks and Submit -->
        <div class="pt-4">
            <x-textarea name="remarks" label="Remarks" placeholder="Write your remarks" />
            <form wire:submit.prevent="approve">
                <div class="flex justify-end pt-4">
                    <x-button type="submit" blue label="Approve" />
                </div>
            </form>

        </div>
    </div>
</div>