<div class="p-6 max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Monthly Cash Flow Summary</h2>
        <a href="{{ route('cash-flow') }}">
            <x-button label="Back to Daily View" primary />
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded shadow border border-gray-200">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-gray-700 text-sm font-semibold uppercase">
                <tr>
                    <th class="px-6 py-3">Month</th>
                    <th class="px-6 py-3">Beginning Balance</th>
                    <th class="px-6 py-3">Customer Payments</th>
                    <th class="px-6 py-3">Payment to Supplier</th>
                    <th class="px-6 py-3">Expenses</th>
                    <th class="px-6 py-3">Ending Balance</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($monthlySummary as $month)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3 font-medium">{{ \Carbon\Carbon::parse($month->month)->format('F Y') }}</td>
                        <td
                            class="px-6 py-3 font-semibold {{ $month->beginning_balance < 0 ? 'text-red-600' : 'text-green-600' }}">
                            ₱{{ number_format($month->beginning_balance, 2) }}
                        </td>
                        <td class="px-6 py-3">₱{{ number_format($month->customer_payments, 2) }}</td>
                        <td class="px-6 py-3">₱{{ number_format($month->payment_to_supplier, 2) }}</td>
                        <td class="px-6 py-3">₱{{ number_format($month->expenses, 2) }}</td>
                        <td
                            class="px-6 py-3 font-semibold {{ $month->ending_balance < 0 ? 'text-red-600' : 'text-green-600' }}">
                            ₱{{ number_format($month->ending_balance, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-4">No data available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>