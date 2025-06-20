<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Unserved PO Report</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* General styles for the page view */
        body {
            font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
            /* Using a more generic font-family */
        }

        /* Styles specifically for print/PDF to ensure things look good */
        @media print {
            .no-print {
                display: none !important;
                /* Ensure buttons are hidden on print */
            }

            body {
                margin: 0;
                padding: 0;
                /* Avoid background image/color on print for ink saving */
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .max-w-3xl {
                max-width: 100% !important;
                /* Allow full width on print if needed */
                box-shadow: none !important;
                /* Remove shadow for print */
                border: none !important;
                /* Remove border for print */
                padding: 0 !important;
                /* Adjust padding for print */
            }

            table {
                width: 100% !important;
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-8 text-gray-800 font-serif">
    {{-- This is the container for the content that will be PRINTED/DOWNLOADED --}}
    <div id="report-content-to-download" class="max-w-3xl mx-auto bg-white p-10 shadow-lg border">
        {{-- Company Header --}}
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-gray-800">Teepee Distribution</h1>
            <p class="text-sm text-gray-500">Email: support@teepee.com | Phone: (123) 456-7890</p>
        </div>

        {{-- Report Title --}}
        <div class="mb-8">
            <p class="text-sm">Date: <strong>{{ now()->format('F d, Y', \Carbon\Carbon::now('Asia/Manila')) }}</strong>
            </p>
            <h2 class="text-xl font-bold text-gray-700 mt-2">Unserved Items Report</h2>
            {{-- Using optional chaining `??` to handle cases where $customer might be null or name doesn't exist --}}
            <p class="text-gray-600 mt-1">Dear {{ $customer->name ?? 'Valued Customer' }},</p>
            <p class="text-gray-600">Please find below the summary of your unserved purchase orders.</p>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="mx-auto table-auto border-collapse text-sm w-full max-w-2xl">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="border px-3 py-2 text-left">PO Date</th>
                        <th class="border px-3 py-2 text-left">Customer</th>
                        <th class="border px-3 py-2 text-left">Product</th>
                        <th class="border px-3 py-2 text-right">PO Qty</th>
                        <th class="border px-3 py-2 text-right">Served</th>
                        <th class="border px-3 py-2 text-right">Unserved</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $row)
                        <tr class="text-gray-800">
                            <td class="border px-3 py-2">{{ $row['date'] }}</td>
                            <td class="border px-3 py-2">{{ $row['customer_name'] }}</td>
                            <td class="border px-3 py-2">{{ $row['product_description'] }}</td>
                            <td class="border px-3 py-2 text-right">{{ $row['po_quantity'] }}</td>
                            <td class="border px-3 py-2 text-right">{{ $row['served_quantity'] }}</td>
                            <td class="border px-3 py-2 text-right text-red-600 font-semibold">{{ $row['difference'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Closing Note --}}
        <div class="mt-8 text-gray-700">
            <p>Should you have any questions or require further clarification, please do not hesitate to contact us.</p>
            <p class="mt-6">Sincerely,</p>
            <p class="font-semibold">Teepee Distribution Team</p>
        </div>
    </div> {{-- End of #report-content-to-download --}}

    {{-- Buttons for print/download (These are OUTSIDE the div to be converted) --}}
    <div class="flex justify-center space-x-3 mt-8 no-print">
        <button onclick="window.print()"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Print</button>
        <button onclick="downloadPDF()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Download
            PDF</button>
        <a href="{{ route('unservered-lacking') }}"
            class="bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded">Back</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Make the customer name available in JavaScript
        // Ensure $customer is passed from your PHP controller/route to this Blade view.
        const customerName = "{{ $customer->name ?? 'report' }}"; // 'report' as a fallback
        const formattedDate = "{{ now()->format('Ymd', \Carbon\Carbon::now('Asia/Manila')) }}";

        function downloadPDF() {
            const element = document.getElementById('report-content-to-download');

            // Sanitize customerName for use in a filename (remove special characters, spaces, etc.)
            // Replace spaces with underscores, remove non-alphanumeric characters
            const sanitizedCustomerName = customerName.replace(/[^a-zA-Z0-9\s]/g, '').replace(/\s+/g, '_');

            const opt = {
                margin: 0.5,
                filename: `${sanitizedCustomerName}_${formattedDate}.pdf`, // Dynamic filename using customer name
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, logging: false, dpi: 192, letterRendering: true },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            html2pdf().set(opt).from(element).save();
        }
    </script>
</body>

</html>