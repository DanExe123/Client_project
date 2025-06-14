<div class="print-area w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6 bg-white print:border-0 print:p-0">
    {{-- Header --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Invoice</h2>
        <span class="text-sm text-gray-600">Date: {{ $printInvoiceData->order_date?->format('Y-m-d') }}</span>
    </div>

    {{-- Supplier & PO Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
        <div>
            <p><strong>PO Number:</strong> {{ $printInvoiceData->po_number }}</p>
            <p><strong>Status:</strong> {{ $printInvoiceData->status }}</p>
        </div>
        <div>
            <p><strong>Supplier:</strong> {{ $printInvoiceData->supplier->name ?? 'N/A' }}</p>
            <p><strong>Total Amount:</strong> ₱{{ number_format($printInvoiceData->total_amount, 2) }}</p>
        </div>
    </div>

    {{-- Items Table --}}
    @if($printInvoiceData->items && count($printInvoiceData->items))
    <div class="mt-6">
        <h3 class="font-semibold text-gray-700 mb-2">Items</h3>
        <table class="w-full text-sm text-left border border-gray-200">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-2 border">Item</th>
                    <th class="p-2 border">Quantity</th>
                    <th class="p-2 border">Price</th>
                    <th class="p-2 border">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($printInvoiceData->items as $item)
                    <tr class="border-t">
                        <td class="p-2 border">{{ $item->product_name }}</td>
                        <td class="p-2 border">{{ $item->quantity }}</td>
                        <td class="p-2 border">₱{{ number_format($item->price, 2) }}</td>
                        <td class="p-2 border">₱{{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

   {{-- Footer --}}
<div class="mt-6 flex justify-between print:hidden">
    <a href="{{ route('recieving') }}">
        <x-button label="Back" primary flat class="!text-sm" />
    </a>
    <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded text-sm">
        Print Invoice
    </button>
</div>

</div>
