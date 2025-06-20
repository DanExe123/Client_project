<div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6 bg-white">
    {{-- Header --}}
    <div class="flex justify-start">
        <h2 class="text-lg font-bold text-gray-800">View transaction</h2>
    </div>

    {{-- Breadcrumb --}}
    <div class="text-gray-500 flex text-start gap-3">
        <a href="{{ route('account-recievables') }}">
            <span class="text-gray-500 font-medium">Account Recievables</span>
        </a>
        <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
        <span class="text-gray-500 font-medium">View Transactions</span>
    </div>

    <hr>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 text-sm text-gray-700">
        <div>
            <div class="overflow-auto max-h-[70vh]">
                <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-700">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Transaction Type</th>
                            <th class="px-4 py-3">Reference Number</th>
                            <th class="px-4 py-3">Credit</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3">Sales</th>
                            <th class="px-4 py-3">Running Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($transactions as $item)
                            <tr>
                                <td class="px-4 py-2">
                                    {{ \Carbon\Carbon::parse($item['created_at'])->format('Y-m-d h:i A') }}
                                </td>
                                <td class="px-4 py-2">{{ $item['type'] }}</td>
                                <td class="px-4 py-2">{{ $item['reference'] }}</td>
                                <td class="px-4 py-2">₱{{ number_format($item['credit'], 2) }}</td>
                                <td class="px-4 py-2">₱{{ number_format($item['payment'], 2) }}</td>
                                <td class="px-4 py-2">₱{{ number_format($item['sales'], 2) }}</td>
                                <td class="px-4 py-2">
                                    ₱{{ is_null($item['running_balance']) ? 'Running Balance' : number_format($item['running_balance'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-4">No transactions available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="font-semibold bg-gray-50">
                            <td colspan="6" class="text-right px-4 py-3">Total Balance:</td>
                            <td class="px-4 py-3 text-green-600">
                                ₱{{ number_format($totalBalance ?? 0, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="flex justify-center gap-6 mt-6">
        <a href="{{ route('account-recievables') }}">
            <x-button label="Back" primary flat class="!text-sm mt-2" />
        </a>
    </div>
</div>