<div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4">
  <h2 class="text-xl font-semibold mb-4">Sales Book</h2>

  <div class="p-6 max-w-7xl mx-auto">
    <!-- Date Filters -->
    <div class="flex gap-4 mb-6">
      <div>
        <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
        <input type="date" id="startDate" wire:model.lazy="startDate"
          class="border border-gray-300 rounded px-4 py-2 w-full" />
      </div>
      <div>
        <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
        <input type="date" id="endDate" wire:model.lazy="endDate"
          class="border border-gray-300 rounded px-4 py-2 w-full" />
      </div>
    </div>

    <!-- Loading Spinner -->
    <div wire:loading.delay.longest wire:target="startDate,endDate" class="flex justify-center items-center py-10">
      <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-400 border-t-transparent"></div>
      <span class="ml-3 text-blue-600 text-sm font-semibold">Loading sales data...</span>
    </div>

    <!-- Conditionally Show Table -->
    @if ($showTable)
    <div wire:loading.remove wire:target="startDate,endDate"
      class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4 transition-opacity duration-500">
      <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
      <thead class="bg-gray-50">
        <tr>
        <th class="px-4 py-2 font-semibold">Date</th>
        <th class="px-4 py-2 font-semibold">Invoice No.</th>
        <th class="px-4 py-2 font-semibold">Customer Name</th>
        <th class="px-4 py-2 font-semibold">Tin No.</th>
        <th class="px-4 py-2 font-semibold">Address</th>
        <th class="px-4 py-2 text-right font-semibold">Gross Amount</th>
        <th class="px-4 py-2 text-right font-semibold">VAT</th>
        <th class="px-4 py-2 text-right font-semibold">Amount Net of Vat</th>
        <th class="px-4 py-2 font-semibold">Payment Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 border-t border-gray-100 bg-white">
        @forelse ($sales as $sale)
      <tr>
      <td class="px-4 py-2">{{ \Carbon\Carbon::parse($sale->release_date)->format('Y-m-d') }}</td>
      <td class="px-4 py-2">INV-{{ $sale->id }}</td>
      <td class="px-4 py-2">{{ $sale->customer->name ?? 'N/A' }}</td>
      <td class="px-4 py-2">{{ $sale->customer->cust_tin_number ?? '-' }}</td>
      <td class="px-4 py-2">{{ $sale->customer->address ?? '-' }}</td>
      <td class="px-4 py-2 text-right">₱{{ number_format($sale->total_amount, 2) }}</td>
      <td class="px-4 py-2 text-right">₱{{ number_format($sale->add_vat, 2) }}</td>
      <td class="px-4 py-2 text-right">₱{{ number_format($sale->amount_net_of_vat, 2) }}</td>
      <td class="px-4 py-2">-</td>
      </tr>
      @empty
      <tr>
      <td colspan="9" class="px-4 py-6 text-center text-gray-500">No sales found for selected dates.</td>
      </tr>
      @endforelse
      </tbody>
      </table>
    </div>
  @endif
  </div>
</div>