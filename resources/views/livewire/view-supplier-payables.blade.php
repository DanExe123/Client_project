<div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6 bg-white">
    {{-- Header --}}
    <div class="flex justify-start">
        <h2 class="text-lg font-bold text-gray-800">View Payables for {{ $supplier->name }}</h2>
    </div>

    {{-- Breadcrumb --}}
    <div class="text-gray-500 flex text-start gap-3">
        <a href="{{ route('payable-ledger') }}">
            <span class="text-gray-500 font-medium">Account Payables</span>
        </a>
        <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
        <span class="text-gray-500 font-medium">View Transactions</span>
    </div>

    <hr>
    <div class="grid grid-cols-1 md:grid-cols-1 gap-6 text-sm text-gray-700">
        <div>
            <div class="overflow-auto max-h-[70vh]">
                @php
                    $transactions = collect();

                    foreach ($receiveditem as $item) {
                        $transactions->push([
                            'date' => $item->created_at,
                            'type' => 'Receiving',
                            'reference' => 'RCV-' . $item->id,
                            'return' => 0,
                            'payment' => 0,
                            'received' => $item->grand_total,
                        ]);
                    }

                    foreach ($payments as $item) {
                        $transactions->push([
                            'date' => $item->created_at,
                            'type' => 'Payment',
                            'reference' => 'PAY-' . $item->id,
                            'return' => 0,
                            'payment' => $item->amount_paid,
                            'received' => 0,
                        ]);
                    }

                    foreach ($returns as $item) {
                        $transactions->push([
                            'date' => $item->approved_at,
                            'type' => 'Return',
                            'reference' => 'RET-' . $item->id,
                            'return' => $item->total_amount,
                            'payment' => 0,
                            'received' => 0,
                        ]);
                    }

                    $transactions = $transactions->sortBy('date')->values();
                    $runningBalance = 0;
                @endphp

                <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-700">
                    <thead class="bg-gray-100 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3">Date</th>
                            <th class="px-4 py-3">Transaction Type</th>
                            <th class="px-4 py-3">Reference Number</th>
                            <th class="px-4 py-3 text-right">Return</th>
                            <th class="px-4 py-3 text-right">Payment</th>
                            <th class="px-4 py-3 text-right">Received</th>
                            <th class="px-4 py-3 text-right">Running Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($transactions as $txn)
                            @php
                                $runningBalance += $txn['received'];
                                $runningBalance -= $txn['payment'];
                                $runningBalance -= $txn['return'];
                            @endphp
                            <tr>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($txn['date'])->format('Y-m-d h:i A') }}</td>
                                <td class="px-4 py-2">{{ $txn['type'] }}</td>
                                <td class="px-4 py-2">{{ $txn['reference'] }}</td>
                                <td class="px-4 py-2 text-right ">
                                    {{ $txn['return'] > 0 ? '₱' . number_format($txn['return'], 2) : '' }}
                                </td>
                                <td class="px-4 py-2 text-right ">
                                    {{ $txn['payment'] > 0 ? '₱' . number_format($txn['payment'], 2) : '' }}
                                </td>
                                <td class="px-4 py-2 text-right ">
                                    {{ $txn['received'] > 0 ? '₱' . number_format($txn['received'], 2) : '' }}
                                </td>
                                <td class="px-4 py-2 text-right  font-medium">
                                    ₱{{ number_format($runningBalance, 2) }}
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
                            <td class="px-4 py-3 text-green-600 text-right">₱{{ number_format($runningBalance, 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="flex justify-center gap-6 mt-6">
        <a href="{{ route('payable-ledger') }}">
            <x-button label="Back" primary flat class="!text-sm mt-2" />
        </a>
    </div>
</div>