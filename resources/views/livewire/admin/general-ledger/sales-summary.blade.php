<div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4" x-data="salesSummary()">
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
      <template x-for="sale in filteredSales()" :key="sale.invoice_number + sale.product_name">
        <tr>
          <td class="px-4 py-2" x-text="sale.date"></td>
          <td class="px-4 py-2" x-text="sale.invoice_number"></td>
          <td class="px-4 py-2" x-text="sale.customer_name"></td>
          <td class="px-4 py-2" x-text="sale.product_name"></td>
          <td class="px-4 py-2 text-right" x-text="sale.quantity_sold"></td>
          <td class="px-4 py-2 text-right" x-text="formatCurrency(sale.unit_price)"></td>
          <td class="px-4 py-2 text-right" x-text="formatCurrency(sale.gross_sales)"></td>
          <td class="px-4 py-2 text-right" x-text="formatCurrency(sale.discount)"></td>
          <td class="px-4 py-2 text-right" x-text="formatCurrency(sale.returns)"></td>
          <td class="px-4 py-2 text-right" x-text="formatCurrency(sale.net_sales)"></td>
          <td class="px-4 py-2" x-text="sale.payment_status"></td>
          <td class="px-4 py-2" x-text="sale.payment_type || '-'"></td>
        </tr>
      </template>
      <tr x-show="filteredSales().length === 0">
        <td colspan="12" class="px-4 py-2 text-center text-gray-500">No sales data found for selected date range.</td>
      </tr>
    </tbody>
  </table>
</div>

<script>
  function salesSummary() {
    return {
      startDate: '',
      endDate: '',
      sales: [
        {
          date: '2025-06-01',
          invoice_number: 'INV001',
          customer_name: 'John Doe',
          product_name: 'Dog Food',
          quantity_sold: 5,
          unit_price: 100,
          gross_sales: 500,
          discount: 50,
          returns: 0,
          net_sales: 450,
          payment_status: 'Paid',
          payment_type: 'Cash',
        },
        {
          date: '2025-06-02',
          invoice_number: 'INV002',
          customer_name: 'Jane Smith',
          product_name: 'Cat Toy',
          quantity_sold: 2,
          unit_price: 150,
          gross_sales: 300,
          discount: 0,
          returns: 10,
          net_sales: 290,
          payment_status: 'Pending',
          payment_type: null,
        },
        // More sales data here...
      ],
      filteredSales() {
        const start = this.startDate ? new Date(this.startDate) : null;
        const end = this.endDate ? new Date(this.endDate) : null;
        return this.sales.filter(sale => {
          const saleDate = new Date(sale.date);
          if (start && saleDate < start) return false;
          if (end && saleDate > end) return false;
          return true;
        });
      },
      formatCurrency(value) {
        return '₱' + value.toFixed(2);
      },
    }
  }
</script>
