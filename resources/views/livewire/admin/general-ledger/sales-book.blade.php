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
          <template x-for="sale in filteredSales" :key="sale.invoice_no">
            <tr>
              <td class="px-4 py-2" x-text="sale.date"></td>
              <td class="px-4 py-2" x-text="sale.invoice_no"></td>
              <td class="px-4 py-2" x-text="sale.customer_name"></td>
              <td class="px-4 py-2" x-text="sale.tin_no"></td>
              <td class="px-4 py-2" x-text="sale.address"></td>
              <td class="px-4 py-2 text-right" x-text="formatCurrency(sale.gross_amount)"></td>
              <td class="px-4 py-2 text-right" x-text="formatCurrency(sale.vat)"></td>
              <td class="px-4 py-2 text-right" x-text="formatCurrency(sale.net_amount)"></td>
              <td class="px-4 py-2" x-text="sale.payment_status"></td>
            </tr>
          </template>

          <tr x-show="filteredSales.length === 0">
            <td colspan="9" class="px-4 py-6 text-center text-gray-500">No sales found for selected dates.</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    function salesBook() {
      return {
        startDate: '',
        endDate: '',
        sales: [
          {
            date: '2025-06-01',
            invoice_no: 'INV-1001',
            customer_name: 'Acme Corp',
            tin_no: '123-456-789',
            address: '123 Main St, Cityville',
            gross_amount: 1000,
            vat: 120,
            net_amount: 1120,
            payment_status: 'Paid',
          },
          {
            date: '2025-06-05',
            invoice_no: 'INV-1002',
            customer_name: 'Beta LLC',
            tin_no: '987-654-321',
            address: '456 Side Rd, Townsville',
            gross_amount: 2000,
            vat: 240,
            net_amount: 2240,
            payment_status: 'Pending',
          },
          // Add more sample data as needed
        ],
        get filteredSales() {
          if (!this.startDate && !this.endDate) return this.sales;

          return this.sales.filter(sale => {
            const saleDate = new Date(sale.date);
            const start = this.startDate ? new Date(this.startDate) : null;
            const end = this.endDate ? new Date(this.endDate) : null;

            if (start && end) {
              return saleDate >= start && saleDate <= end;
            } else if (start) {
              return saleDate >= start;
            } else if (end) {
              return saleDate <= end;
            }
            return true;
          });
        },
        formatCurrency(value) {
          return '₱' + value.toFixed(2);
        }
      }
    }
  </script>
</div>
