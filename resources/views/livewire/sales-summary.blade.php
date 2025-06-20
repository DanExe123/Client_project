<div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4">
  <div class="mb-8">
    <h2 class="text-xl font-semibold mb-4">Compare Monthly Sales</h2>

    <div class="flex gap-4 flex-wrap items-end mb-6">
      <div>
        <label class="text-sm font-medium text-gray-700">Month 1</label>
        <select id="month1" class="border-gray-300 rounded-md shadow-sm mt-1">
          @foreach ($monthlySales as $month)
        <option value="{{ $month['month'] }}">{{ \Carbon\Carbon::parse($month['month'])->format('F Y') }}</option>
      @endforeach
        </select>
      </div>
      <div>
        <label class="text-sm font-medium text-gray-700">Month 2</label>
        <select id="month2" class="border-gray-300 rounded-md shadow-sm mt-1">
          @foreach ($monthlySales as $month)
        <option value="{{ $month['month'] }}">{{ \Carbon\Carbon::parse($month['month'])->format('F Y') }}</option>
      @endforeach
        </select>
      </div>
    </div>

    <!-- Two-Column Chart Layout -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
      <!-- Gross Sales Chart -->
      <div class="bg-white border rounded p-4 shadow-sm">
        <h3 class="text-md font-semibold mb-2 text-center">Gross Sales</h3>
        <canvas id="grossChart" height="100"></canvas>
      </div>

      <!-- Net Sales Chart -->
      <div class="bg-white border rounded p-4 shadow-sm">
        <h3 class="text-md font-semibold mb-2 text-center">Net Sales</h3>
        <canvas id="netChart" height="100"></canvas>
      </div>
    </div>
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
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const monthlySales = @json($monthlySales);

    const grossCtx = document.getElementById('grossChart').getContext('2d');
    const netCtx = document.getElementById('netChart').getContext('2d');

    let grossChart, netChart;

    function formatMonth(month) {
      const [year, monthNum] = month.split('-');
      return new Date(year, monthNum - 1).toLocaleString('default', { month: 'long', year: 'numeric' });
    }

    function updateCharts(month1, month2) {
      const data1 = monthlySales.find(m => m.month === month1);
      const data2 = monthlySales.find(m => m.month === month2);

      // Even if same month, still render chart
      const labels = [formatMonth(month1), formatMonth(month2)];

      const grossValues = [
        data1 ? data1.gross_sales : 0,
        data2 ? data2.gross_sales : 0
      ];

      const netValues = [
        data1 ? data1.net_sales : 0,
        data2 ? data2.net_sales : 0
      ];

      const grossData = {
        labels,
        datasets: [{
          label: 'Gross Sales',
          data: grossValues,
          backgroundColor: ['rgba(59,130,246,0.2)', 'rgba(16,185,129,0.2)'],
          borderColor: ['rgba(59,130,246,1)', 'rgba(16,185,129,1)'],
          borderWidth: 2,
          fill: false,
          tension: 0.3
        }]
      };

      const netData = {
        labels,
        datasets: [{
          label: 'Net Sales',
          data: netValues,
          backgroundColor: ['rgba(245,158,11,0.2)', 'rgba(239,68,68,0.2)'],
          borderColor: ['rgba(245,158,11,1)', 'rgba(239,68,68,1)'],
          borderWidth: 2,
          fill: false,
          tension: 0.3
        }]
      };

      const options = {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: value => '₱' + value.toLocaleString()
            }
          }
        }
      };

      if (grossChart) grossChart.destroy();
      if (netChart) netChart.destroy();

      grossChart = new Chart(grossCtx, { type: 'line', data: grossData, options });
      netChart = new Chart(netCtx, { type: 'line', data: netData, options });
    }

    const month1Select = document.getElementById('month1');
    const month2Select = document.getElementById('month2');

    month1Select.addEventListener('change', () => {
      updateCharts(month1Select.value, month2Select.value);
    });

    month2Select.addEventListener('change', () => {
      updateCharts(month1Select.value, month2Select.value);
    });

    // Initial render
    updateCharts(month1Select.value, month2Select.value);
  });
</script>