<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unserved PO Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body class="p-8 text-gray-800">
    <div class="max-w-6xl mx-auto bg-white p-6 shadow-md border">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-700">Teepee</h1>
            <h2 class="text-lg font-bold text-gray-600">UNSERVED ITEMS REPORT</h2>
            <div class="text-sm text-right">
                <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
            </div>
        </div>

        <table class="w-full text-sm border mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">PO Date</th>
                    <th class="border px-3 py-2">Customer</th>
                    <th class="border px-3 py-2">Product</th>
                    <th class="border px-3 py-2 text-right">PO Qty</th>
                    <th class="border px-3 py-2 text-right">Served</th>
                    <th class="border px-3 py-2 text-right">Unserved</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        <td class="border px-3 py-2">{{ $row['date'] }}</td>
                        <td class="border px-3 py-2">{{ $row['customer_name'] }}</td>
                        <td class="border px-3 py-2">{{ $row['product_description'] }}</td>
                        <td class="border px-3 py-2 text-right">{{ $row['po_quantity'] }}</td>
                        <td class="border px-3 py-2 text-right">{{ $row['served_quantity'] }}</td>
                        <td class="border px-3 py-2 text-right text-red-600 font-bold">{{ $row['difference'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div id="pdf-content" class="flex justify-end space-x-3 no-print">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Print</button>
            <button onclick="downloadPDF()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Download PDF</button>
            <a href="{{ route('unservered-lacking') }}" class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">Back</a>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadPDF() {
        const element = document.getElementById('pdf-content');

        const opt = {
            margin:       0.3,
            filename:     'unserved-po-report.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save();
    }
</script>

</body>
</html>
