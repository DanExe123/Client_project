<div x-cloak>
  <div x-data="payableLedger()" class=" w-full mx-auto">
    <!-- Header + Button -->
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold">Payable Ledger</h2>
      <x-button primary label="View Supplier Payable Ledger" x-on:click="open = true" />
    </div>

    <!-- Search Bar -->
    <div class="w-full sm:max-w-xs relative mb-4">
      <span class="absolute inset-y-0 left-0 flex items-center pl-3">
        <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
      </span>
      <input type="text" x-model="search" placeholder="Search by customer..."
        class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
    </div>

    <div class="overflow-auto rounded-lg border border-gray-200">
      <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-700">
        <thead class="bg-gray-50 sticky top-0 z-10">
          <tr>
            <th class="px-4 py-3 font-medium text-gray-900">Supplier Name</th>
            <th class="px-4 py-3 font-medium text-gray-900">Transaction Date</th>
            <th class="px-4 py-3 font-medium text-gray-900">Reference Type</th>
            <th class="px-4 py-3 font-medium text-gray-900">Reference Number</th>
            <th class="px-4 py-3 font-medium text-gray-900 text-center">Credit</th>
            <th class="px-4 py-3 font-medium text-gray-900 text-center">Balance</th>
            <th class="px-4 py-3 font-medium text-gray-900 text-center">Remarks</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
          @foreach ($suppliers as $supplier)
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-2">{{ $supplier->name }}</td>
              <td class="px-4 py-2"></td>
              <td class="px-4 py-2"></td>
              <td class="px-4 py-2"></td>
              <td class="px-4 py-2 text-center"></td>
              <td class="px-4 py-2 text-center"></td>
              <td class="px-4 py-2 text-center"></td>
            </tr>
          @endforeach
    
          @if ($suppliers->isEmpty())
            <tr>
              <td colspan="7" class="text-center p-4 text-gray-500">No suppliers found.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
    
    <!-- Modal -->
    <x-modal-card title="Transactions" name="Transactions">
      <div class="mb-4">
        <h3 class="text-lg font-semibold" x-text="selectedCustomer?.name"></h3>
      </div>

      <!-- Transactions Table -->
      <div class="overflow-auto rounded-lg border border-gray-200">
        <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-700">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-3">Date</th>

            </tr>
          </thead>
          <tbody>
            <template x-for="(txn, index) in selectedCustomer?.transactions || []" :key="index">
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-3" x-text="txn.date"></td>

              </tr>
            </template>
            <tr x-show="selectedCustomer?.transactions?.length === 0">
              <td colspan="7" class="text-center py-4 text-gray-500">No transactions found.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
          <x-button flat label="Close" x-on:click="closeTransactionModal()" />
        </div>
      </x-slot>
    </x-modal-card>
  </div>

  <!-- Alpine Script -->
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('customerReceivables', () => ({
        search: '',
        selectedCustomer: null,
        customers: [
          {
            name: 'John Doe',
            term: '30 Days',
            balance: 1500,
            transactions: [
              { date: '2025-06-01', type: 'Sale', refId: 'INV001', debit: 1500, credit: 0, payment: 0, runningBalance: 1500 },
              { date: '2025-06-05', type: 'Payment', refId: 'PAY001', debit: 0, credit: 500, payment: 500, runningBalance: 1000 }
            ]
          },
          {
            name: 'Jane Smith',
            term: '15 Days',
            balance: 750,
            transactions: [
              { date: '2025-06-02', type: 'Sale', refId: 'INV002', debit: 750, credit: 0, payment: 0, runningBalance: 750 }
            ]
          }
        ],
        get filteredCustomers() {
          return this.customers.filter(c =>
            c.name.toLowerCase().includes(this.search.toLowerCase())
          );
        },
        openTransactionModal(customer) {
          this.selectedCustomer = customer;
          window.dispatchEvent(new CustomEvent('open-modal', { detail: 'customerTransactionModal' }));
        },
        closeTransactionModal() {
          window.dispatchEvent(new CustomEvent('close-modal', { detail: 'customerTransactionModal' }));
        }
      }));
    });
  </script>

</div>