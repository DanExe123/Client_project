<div x-data="payableLedger()" class=" w-full mx-auto">
  <h2 class="text-2xl font-semibold text-gray-900 mb-4">Payable Ledger</h2>
    <!-- Select Supplier -->
    <select id="supplier" x-model="selectedSupplier" @change="loadTransactions()" class="border rounded px-3 py-2 w-full max-w-xs">
        <option value="">Select one supplier</option>
        <template x-for="supplier in suppliers" :key="supplier">
          <option :value="supplier" x-text="supplier"></option>
        </template>
      </select>
  
    <!-- Table -->
    <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mt-4">
      <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-700">
        <thead class="bg-gray-50 sticky top-0 z-10">
          <tr>
            <th class="px-6 py-3 font-semibold">Supplier Name</th>
            <th class="px-6 py-3 font-semibold">Transaction Date</th>
            <th class="px-6 py-3 font-semibold">Reference Type</th>
            <th class="px-6 py-3 font-semibold">Reference Number</th>
            <th class="px-6 py-3 font-semibold text-right">Debit</th>
            <th class="px-6 py-3 font-semibold text-right">Credit</th>
            <th class="px-6 py-3 font-semibold text-right">Running Balance</th>
            <th class="px-6 py-3 font-semibold">Remarks</th>
          </tr>
        </thead>
        <tbody>
          <template x-if="!selectedSupplier">
            <tr>
              <td colspan="8" class="text-center py-8 text-gray-400 italic">Please select a supplier</td>
            </tr>
          </template>
  
          <template x-for="(tx, index) in transactions" :key="tx.id">
            <tr class="hover:bg-gray-50">
              <td class="px-6 py-3" x-text="tx.supplierName"></td>
              <td class="px-6 py-3" x-text="tx.transactionDate"></td>
              <td class="px-6 py-3" x-text="tx.referenceType"></td>
              <td class="px-6 py-3" x-text="tx.referenceNumber"></td>
              <td class="px-6 py-3 text-right" x-text="formatCurrency(tx.debit)"></td>
              <td class="px-6 py-3 text-right" x-text="formatCurrency(tx.credit)"></td>
              <td class="px-6 py-3 text-right" x-text="formatCurrency(runningBalances[index])"></td>
              <td class="px-6 py-3" x-text="tx.remarks"></td>
            </tr>
          </template>
  
          <template x-if="selectedSupplier && transactions.length === 0">
            <tr>
              <td colspan="8" class="text-center py-8 text-gray-400 italic">No transactions found for this supplier.</td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  
  </div>
  
  <script>
    function payableLedger() {
      return {
        selectedSupplier: '',
        suppliers: ['Supplier One', 'Supplier Two', 'Supplier Three'],
        allTransactions: [
          { id: 1, supplierId: 'Supplier One', supplierName: 'Supplier One', transactionDate: '2025-06-01', referenceType: 'Payment', referenceNumber: 'PAY-001', debit: 0, credit: 1000, remarks: 'Payment made' },
          { id: 2, supplierId: 'Supplier One', supplierName: 'Supplier One', transactionDate: '2025-06-05', referenceType: 'Return', referenceNumber: 'RET-002', debit: 200, credit: 0, remarks: 'Returned goods' },
          { id: 3, supplierId: 'Supplier Two', supplierName: 'Supplier Two', transactionDate: '2025-06-03', referenceType: 'Adjustment', referenceNumber: 'ADJ-003', debit: 50, credit: 0, remarks: 'Price adjustment' },
        ],
        transactions: [],
        runningBalances: [],
  
        loadTransactions() {
          if (!this.selectedSupplier) {
            this.transactions = [];
            this.runningBalances = [];
            return;
          }
          this.transactions = this.allTransactions.filter(tx => tx.supplierId === this.selectedSupplier);
  
          let balance = 0;
          this.runningBalances = this.transactions.map(tx => {
            balance += tx.credit - tx.debit;
            return balance;
          });
        },
  
        formatCurrency(value) {
          return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
        }
      }
    }
  </script>
  