<div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4" >
  <h2 class="text-xl font-semibold mb-4">Sales Book</h2>
  <div x-data="salesBook()" class="p-6 max-w-7xl mx-auto">

    <!-- Date Filters -->
    <div class="flex gap-4 mb-6">
      <div>
        <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
        <input type="date" id="startDate" x-model="startDate" class="border border-gray-300 rounded px-4 py-2 w-full" />
      </div>
      <div>
        <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
        <input type="date" id="endDate" x-model="endDate" class="border border-gray-300 rounded px-4 py-2 w-full" />
      </div>
    </div>

    <!-- Sales Book Table -->
    <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4">
      <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-4 py-2 text-left font-semibold">Date</th>
            <th class="px-4 py-2 text-left font-semibold">Invoice No.</th>
            <th class="px-4 py-2 text-left font-semibold">Customer Name</th>
            <th class="px-4 py-2 text-left font-semibold">Tin No.</th>
            <th class="px-4 py-2 text-left font-semibold">Address</th>
            <th class="px-4 py-2 text-right font-semibold">Gross Amount</th>
            <th class="px-4 py-2 text-right font-semibold">VAT</th>
            <th class="px-4 py-2 text-right font-semibold">Net Amount</th>
            <th class="px-4 py-2 text-left font-semibold">Payment Status</th>
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
              <td class="px-4 py-2 text-right">₱{{ number_format($sale->vat_amount, 2) }}</td>
              <td class="px-4 py-2 text-right">₱{{ number_format($sale->total_with_vat, 2) }}</td>
              <td class="px-4 py-2">-</td> {{-- No fetching for this field "payment_status field" --}}
            </tr>
          @empty
            <tr>
              <td colspan="9" class="px-4 py-6 text-center text-gray-500">No sales found for selected dates.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
