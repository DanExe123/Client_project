<div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4">
  <div class="mb-6">
    <h2 class="text-xl font-semibold mb-4">Chart Compare Monthly Sales</h2>
    <div class="flex flex-wrap gap-4 items-center mb-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Month 1</label>
        <select id="month1" class="border-gray-300 rounded-md shadow-sm mt-1">
          @foreach ($monthlySales as $month)
        <option value="{{ $month['month'] }}">{{ \Carbon\Carbon::parse($month['month'])->format('F Y') }}</option>
      @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Month 2</label>
        <select id="month2" class="border-gray-300 rounded-md shadow-sm mt-1">
          @foreach ($monthlySales as $month)
        <option value="{{ $month['month'] }}">{{ \Carbon\Carbon::parse($month['month'])->format('F Y') }}</option>
      @endforeach
        </select>
      </div>
    </div>

    <canvas id="salesComparisonChart" class="w-full max-w-4xl mx-auto" height="200"></canvas>
  </div>
  <h2 class="text-xl font-semibold mb-4">Sales Summary</h2>
  <div class="mt-8 overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">Monthly Sales Summary</h2>

    <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-2 font-semibold">Month</th>
          <th class="px-4 py-2 font-semibold text-right">Total Quantity Sold</th>
          <th class="px-4 py-2 font-semibold text-right">Gross Sales</th>
          <th class="px-4 py-2 font-semibold text-right">Discount</th>
          <th class="px-4 py-2 font-semibold text-right">Returns</th>
          <th class="px-4 py-2 font-semibold text-right">Net Sales</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 border-t border-gray-100">
        @forelse ($monthlySales as $month)
      <tr>
        <td class="px-4 py-2 font-medium font-semibold">
        {{ \Carbon\Carbon::parse($month['month'])->format('F Y') }}
        </td>
        <td class="px-4 py-2 text-right font-semibold">{{ number_format($month['quantity_sold'], 0) }}</td>
        <td class="px-4 py-2 text-right font-semibold">₱{{ number_format($month['gross_sales'], 2) }}</td>
        <td class="px-4 py-2 text-right font-semibold">₱{{ number_format($month['discount'], 0) }}</td>
        <td class="px-4 py-2 text-right font-semibold">₱{{ number_format($month['returns'], 2) }}</td>
        <td class="px-4 py-2 text-right text-green-700 font-semibold">₱{{ number_format($month['net_sales'], 2) }}
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="4" class="text-center text-gray-500 py-4">No monthly data found.</td>
      </tr>
    @endforelse
      </tbody>
    </table>
  </div>
  <br>
  <hr>
  <br>
  <h2 class="text-xl font-semibold mb-4">Daily Sales</h2>
  <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
    <thead class="bg-gray-50">
      <tr>
        <th class="px-4 py-2 font-semibold">Date</th>
        <th class="px-4 py-2 font-semibold">Invoice #</th>
        <th class="px-4 py-2 font-semibold">Customer Name</th>
        <th class="px-4 py-2 font-semibold">Product Name</th>
        <th class="px-4 py-2 font-semibold text-right">Quantity Sold</th>
        <th class="px-4 py-2 font-semibold text-right">Unit Price</th>
        <th class="px-4 py-2 font-semibold text-right">Gross Sales</th>
        <th class="px-4 py-2 font-semibold text-right">Discount</th>
        <th class="px-4 py-2 font-semibold text-right">Returns</th>
        <th class="px-4 py-2 font-semibold text-right">Net Sales</th>
        <th class="px-4 py-2 font-semibold">Payment Status</th>
        <th class="px-4 py-2 font-semibold">Payment Type</th>
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
      <td colspan="12" class="text-center text-gray-500 py-4">No sales data found.</td>
      </tr>
    @endforelse

      @if (count($sales))
      <tr class="bg-gray-50 font-semibold text-gray-700">
      <td colspan="4" class="px-4 py-2 text-right">Total</td>
      <td class="px-4 py-2 text-right">{{ number_format($totalQuantity, 0) }}</td>
      <td></td>
      <td class="px-4 py-2 text-right">{{ number_format($totalGross, 2) }}</td>
      <td class="px-4 py-2 text-right">{{ number_format($totalDiscount, 2) }}</td>
      <td class="px-4 py-2 text-right">{{ number_format($totalReturns, 2) }}</td>
      <td class="px-4 py-2 text-right">{{ number_format($totalNet, 2) }}</td>
      <td></td>
      <td></td>
      </tr>
    @endif
    </tbody>
  </table>
  <hr>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>