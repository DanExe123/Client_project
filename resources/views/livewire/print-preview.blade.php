<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <title>Print Preview - Sales Release #{{ $release->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body class="p-8 text-gray-800">
    <div class="max-w-4xl mx-auto bg-white p-6 shadow-md border">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Teepee</h1>
            <h1 class="text-1xl font-bold">
                {{ $release->receipt_type === 'DR' ? 'DR RECEIPT' : 'Invoice Receipt' }}

            </h1>
            <div class="text-sm text-right">
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($release->release_date)->format('F d, Y') }}</p>
                <p><strong class="font-bold">Invoice No.</strong> <span
                        class="text-red-600 font-bold">{{ $release->id }}</span></p>
            </div>
        </div>
        <div class="mb-6">
            <p><strong>Name:</strong> {{ $release->customer->name }}</p>
            <p><strong>Receipt Type:</strong> {{ $release->receipt_type }}</p>
        </div>

        <table class="w-full text-sm border mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">Product</th>
                    <th class="border px-3 py-2">Barcode</th>
                    <th class="border px-3 py-2 text-right">Qty</th>
                    <th class="border px-3 py-2 text-right">Unit Price</th>
                    <th class="border px-3 py-2 text-right">Discount</th>
                    <th class="border px-3 py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($release->items as $item)
                    <tr>
                        <td class="border px-3 py-2">{{ $item->product_description }}</td>
                        <td class="border px-3 py-2">{{ $item->product_barcode ?? '-' }}</td>
                        <td class="border px-3 py-2 text-right">{{ $item->quantity }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($item->unit_price, 2) }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($item->discount, 2) }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @php
            $grossSales = $release->items->sum('subtotal');
            $discount = $release->discount ?? 0;
            $totalSales = $grossSales - $discount;
        @endphp

        <div class="flex justify-end mb-4">
            <div class="w-full max-w-sm space-y-1">
                <div class="flex justify-between">
                    <span class="font-medium">Total Discount:</span>
                    <span>{{ number_format($discount, 2) }}</span>
                </div>
                <div class="flex justify-between text-lg font-bold">
                    <span>Total Sales:</span>
                    <span>{{ number_format($totalSales, 2) }}</span>
                </div>

                @if ($release->receipt_type !== 'DR')
                    @php
                        $netOfVat = $totalSales / 1.12;
                        $vat = $netOfVat * 0.12;
                        $totalAmountDue = $netOfVat + $vat;
                    @endphp
                    <div class="flex justify-between">
                        <span class="font-medium">Amount Net of VAT:</span>
                        <span>{{ number_format($netOfVat, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Add: VAT (12%):</span>
                        <span>{{ number_format($vat, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold">
                        <span>TOTAL AMOUNT DUE:</span>
                        <span>{{ number_format($totalAmountDue, 2) }}</span>
                    </div>
                @else
                    <div class="flex justify-between text-lg font-bold">
                        <span>TOTAL AMOUNT DUE:</span>
                        <span>{{ number_format($totalSales, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="mb-4">
            <h2 class="font-semibold">Remarks:</h2>
            <p>{{ $release->remarks ?? '' }}</p>
        </div>

        <div class="flex justify-end space-x-3 no-print">
            <button onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Print</button>
            <a href="{{ route('sales-releasing') }}"
                class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">Back</a>
        </div>
        @if ($release->printed_at)
            <div class="fixed left-0 right-0 text-center text-gray-400 text-sm ">

            </div>
        @endif
    </div>

</body>

</html>