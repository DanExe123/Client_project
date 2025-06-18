<div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4">
  <h2 class="text-xl font-semibold mb-4">Sales Summary</h2>

  <!-- Date filter inputs -->
  <div class="flex space-x-4 mb-4">
    <div>
      <label class="block text-gray-700 mb-1" for="startDate">Start Date</label>
      <input type="date" id="startDate" x-model="startDate" class="border rounded px-3 py-1" />
    </div>
    <div>
      <label class="block text-gray-700 mb-1" for="endDate">End Date</label>
      <input type="date" id="endDate" x-model="endDate" class="border rounded px-3 py-1" />
    </div>
  </div>

  <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
    <thead class="bg-gray-50">
      <tr>
        <th class="px-4 py-2 text-left font-semibold">Date</th>
        <th class="px-4 py-2 text-left font-semibold">Invoice #</th>
        <th class="px-4 py-2 text-left font-semibold">Customer Name</th>
        <th class="px-4 py-2 text-left font-semibold">Product Name</th>
        <th class="px-4 py-2 text-right font-semibold">Quantity Sold</th>
        <th class="px-4 py-2 text-right font-semibold">Unit Price</th>
        <th class="px-4 py-2 text-right font-semibold">Gross Sales</th>
        <th class="px-4 py-2 text-right font-semibold">Discount</th>
        <th class="px-4 py-2 text-right font-semibold">Returns</th>
        <th class="px-4 py-2 text-right font-semibold">Net Sales</th>
        <th class="px-4 py-2 text-left font-semibold">Payment Status</th>
        <th class="px-4 py-2 text-left font-semibold">Payment Type</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
      @forelse ($sales as $sale)
        <tr>
          <td class="px-4 py-2">{{ $sale['date'] }}</td>
          <td class="px-4 py-2">INV-{{ $sale['invoice_number'] }}</td>
          <td class="px-4 py-2">{{ $sale['customer_name'] }}</td>
          <td class="px-4 py-2">{{ $sale['product_name'] }}</td>
          <td class="px-4 py-2 text-right">{{ $sale['quantity_sold'] }}</td>
          <td class="px-4 py-2 text-right">{{ number_format($sale['unit_price'], 2) }}</td>
          <td class="px-4 py-2 text-right">{{ number_format($sale['gross_sales'], 2) }}</td>
          <td class="px-4 py-2 text-right">{{ number_format($sale['discount'], 2) }}</td>
          <td class="px-4 py-2 text-right">{{ number_format($sale['returns'], 2) }}</td>
          <td class="px-4 py-2 text-right">{{ number_format($sale['net_sales'], 2) }}</td>
          <td class="px-4 py-2 text-center">{{ $sale['payment_status'] }}</td>
          <td class="px-4 py-2 text-center">{{ $sale['payment_type'] }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="12" class="text-center text-gray-500">No sales data found.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>


