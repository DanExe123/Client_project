<div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6 bg-white">
    {{-- Header --}}
    <div class="flex justify-start">
        <h2 class="text-lg font-bold text-gray-800">View</h2>
    </div>

    {{-- Breadcrumb --}}
    <div class="text-gray-500 flex text-start gap-3">
        <a href="{{ route('recieving') }}">
            <span class="text-gray-500 font-medium">Receiving</span>
        </a>
        <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
        <span class="text-gray-500 font-medium">View detail receiving</span>
    </div>

    <hr>

    {{-- Purchase Order Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
        <div>
            <p><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</p>
            <p><strong>Receipt Type:</strong> {{ $purchaseOrder->receipt_type }}</p>
            <p><strong>Status:</strong> {{ $purchaseOrder->status }}</p>
        </div>
        <div>
            <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($purchaseOrder->order_date)->format('F d, Y') }}</p>
            <p><strong>Total Amount:</strong> ₱{{ number_format($purchaseOrder->total_amount, 2) }}</p>
            <p><strong>Remarks:</strong> {{ $purchaseOrder->remarks ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Items Table --}}
    @if($purchaseOrder->items && $purchaseOrder->items->count())
        <div class="mt-6">
            <h3 class="font-semibold text-gray-700 mb-2">Items</h3>
            <table class="w-full text-sm text-left border border-gray-200">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-2 border">Barcode</th>
                        <th class="p-2 border">Description</th>
                        <th class="p-2 border">Qty</th>
                        <th class="p-2 border">Unit Price</th>
                        <th class="p-2 border">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->items as $item)
                        <tr class="border-t">
                            <td class="p-2 border">{{ $item->product_barcode }}</td>
                            <td class="p-2 border">{{ $item->product_description }}</td>
                            <td class="p-2 border">{{ $item->quantity }}</td>
                            <td class="p-2 border">₱{{ number_format($item->unit_price, 2) }}</td>
                            <td class="p-2 border">₱{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-sm text-gray-500 mt-4">
            No items found for this purchase order.
        </div>
    @endif

    {{-- Back Button --}}
    <div class="flex justify-center gap-6 mt-6">
        <a href="{{ route('recieving') }}">
            <x-button label="Back" primary flat class="!text-sm mt-2" />
        </a>
    </div>
</div>
