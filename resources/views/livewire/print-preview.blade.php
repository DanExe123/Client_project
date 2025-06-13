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
            <h1 class="text-2xl font-bold">Sales Release Receipt</h1>
            <div class="text-sm text-right">
                <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($release->release_date)->format('F d, Y') }}</p>
                <p><strong>Release ID:</strong> #{{ $release->id }}</p>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="font-semibold text-lg">Customer Information</h2>
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

        <div class="text-right mb-4">
            <p><strong>Total Discount:</strong> {{ number_format($release->discount, 2) }}</p>
            <p class="text-lg font-bold">Total Amount:
                {{ number_format($release->items->sum('subtotal'), 2) }}
            </p>
        </div>

        <div class="mb-4">
            <h2 class="font-semibold">Remarks</h2>
            <p>{{ $release->remarks ?? 'N/A' }}</p>
        </div>

        <div class="flex justify-end space-x-3 no-print">
            <button onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Print</button>
            <a href="{{ url()->previous() }}"
                class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">Back</a>
        </div>
    </div>
</body>

</html>