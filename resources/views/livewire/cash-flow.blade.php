<div>
  <!-- Cash Flow Table -->
  <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4"
    x-data="cashFlowComponent(@js($cashFlowEntries))">

    <h2 class="text-xl font-semibold mb-4">Cash Flow Summary</h2>

    <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
      <thead class="bg-gray-50 sticky top-0 z-10">
        <tr>
          <th class="px-6 py-4 font-medium text-gray-900">Date</th>
          <th class="px-6 py-4 font-medium text-gray-900">Beginning Balance</th>
          <th class="px-6 py-4 font-medium text-gray-900">Customer Payments</th>
          <th class="px-6 py-4 font-medium text-gray-900">Payment to Supplier</th>
          <th class="px-6 py-4 font-medium text-gray-900">Expenses</th>
          <th class="px-6 py-4 font-medium text-gray-900">Ending Balance</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 border-t border-gray-100">
        <template x-for="(entry, index) in cashFlowEntries" :key="index">
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4" x-text="formatDate(entry.date)"></td>
            <td class="px-6 py-4" x-text="formatCurrency(entry.beginning_balance)"></td>
            <td class="px-6 py-4" x-text="formatCurrency(entry.customer_payments)"></td>
            <td class="px-6 py-4" x-text="formatCurrency(entry.payment_to_supplier)"></td>
            <td class="px-6 py-4" x-text="formatCurrency(entry.expenses)"></td>
            <td class="px-6 py-4" x-text="formatCurrency(entry.ending_balance)"></td>
          </tr>
        </template>
        <tr x-show="cashFlowEntries.length === 0">
          <td colspan="5" class="px-6 py-4 text-center text-gray-500">No cash flow data available.</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<script>
  function cashFlowComponent(data) {
    return {
      cashFlowEntries: data,
      formatCurrency(value) {
        return '₱' + parseFloat(value).toFixed(2);
      },
      formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', {
          year: 'numeric',
          month: 'short',
          day: 'numeric'
        });
      }
    };
  }
</script>