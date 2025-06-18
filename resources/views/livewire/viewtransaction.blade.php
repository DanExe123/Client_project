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
                            <th class="px-4 py-3">Reference ID</th>
                            <th class="px-4 py-3">Credit</th>
                            <th class="px-4 py-3">Payment</th>
                            <th class="px-4 py-3">Sales</th>
                            <th class="px-4 py-3">Running Balance</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        {{-- Released Items --}}
                        @if ($releasedItems->count() > 0)
                            @foreach ($releasedItems as $release)
                                <tr>
                                    <td class="px-4 py-2">{{ $release->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2">Released Item</td>
                                    <td class="px-4 py-2">RI-{{ str_pad($release->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($release->credit ?? 0, 2) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($release->payment ?? 0, 2) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($release->payment ?? 0, 2) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($release->running_balance ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
    
                        {{-- Sales Releases --}}
                        @if ($salesReleases->count() > 0)
                            @foreach ($salesReleases as $sale)
                                <tr>
                                    <td class="px-4 py-2">{{ $sale->created_at->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2">Sales Release</td>
                                    <td class="px-4 py-2">SR-{{ str_pad($sale->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($sale->credit ?? 0, 2) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($sale->payment ?? 0, 2) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($sale->payment ?? 0, 2) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($sale->total_amount ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
    
                        {{-- Return Credits --}}
                        @if ($returnCredits->count() > 0)
                            @foreach ($returnCredits as $return)
                                <tr>
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($return->order_date)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2">Credit</td>
                                    <td class="px-4 py-2">C-{{ str_pad($return->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($return->unit_price ?? 0, 2) }}</td>
                                    <td class="px-4 py-2">₱0.00</td>
                                    <td class="px-4 py-2">₱0.00</td>
                                    <td class="px-4 py-2">₱{{ number_format($return->subtotal ?? 0, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
    
                        {{-- No Records --}}
                        @if (
                            $releasedItems->count() === 0 &&
                            $salesReleases->count() === 0 &&
                            $returnCredits->count() === 0
                        )
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-4">No transactions available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
    
            {{-- Pagination --}}
            <div class="mt-4 space-y-4">
                @if ($releasedItems->hasPages())
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-1">Released Items Pagination</h3>
                        {{ $releasedItems->links() }}
                    </div>
                @endif
    
                @if ($salesReleases->hasPages())
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-1">Sales Releases Pagination</h3>
                        {{ $salesReleases->links() }}
                    </div>
                @endif
    
                @if ($returnCredits->hasPages())
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-1">Return Credits Pagination</h3>
                        {{ $returnCredits->links() }}
                    </div>
                @endif
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
