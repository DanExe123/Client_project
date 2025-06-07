<div>
  <h2 class="text-xl font-semibold">Payment Application</h2>
    <div x-data="paymentApplication()" class="p-4 space-y-6 max-w-6xl mx-auto border rounded-lg border-gray-200">

        <!-- Select Date -->
        <div>
          <label for="date" class="block mb-1 font-semibold">Select Date</label>
          <input type="date" id="date" x-model="date" class="border rounded px-3 py-2 w-full" />
        </div>
      
        <!-- Select Customer -->
        <div>
          <label for="customer" class="block mb-1 font-semibold">Select Customer</label>
          <select id="customer" x-model="customer" class="border rounded px-3 py-2 w-full">
            <option value="" disabled>Select Customer</option>
            <option value="Customer A">Customer A</option>
            <option value="Customer B">Customer B</option>
            <option value="Customer C">Customer C</option>
          </select>
        </div>
      
        <!-- Select Invoice/DR -->
        <div>
          <label class="block mb-1 font-semibold">Select Invoice/DR</label>
          <select x-model="selectedInvoice" @change="addInvoice()" class="border rounded px-3 py-2 w-full">
            <option value="" disabled selected>Select Invoice/DR</option>
            <template x-for="inv in invoices" :key="inv.number">
              <option :value="inv.number" x-text="inv.number + ' - ' + inv.date + ' - ₱' + inv.amount.toFixed(2)"></option>
            </template>
          </select>
        </div>
      
        <!-- Invoices Table -->
        <div class="overflow-auto rounded border border-gray-300 mt-3">
          <table class="w-full text-left text-sm text-gray-700">
            <thead class="bg-gray-100">
              <tr>
                <th class="px-4 py-2">Invoice Number</th>
                <th class="px-4 py-2">Invoice Date</th>
                <th class="px-4 py-2">Invoice Amount</th>
                <th class="px-4 py-2">Remove</th>
              </tr>
            </thead>
            <tbody>
              <template x-for="(inv, index) in selectedInvoices" :key="inv.number">
                <tr class="border-t">
                  <td class="px-4 py-2" x-text="inv.number"></td>
                  <td class="px-4 py-2" x-text="inv.date"></td>
                  <td class="px-4 py-2" x-text="'₱' + inv.amount.toFixed(2)"></td>
                  <td class="px-4 py-2 text-center">
                    <button @click="removeInvoice(index)" class="text-red-600 hover:underline">Remove</button>
                  </td>
                </tr>
              </template>
            </tbody>
            <tfoot class="bg-gray-100 font-semibold">
              <tr>
                <td colspan="2" class="px-4 py-2 text-right">Total Amount:</td>
                <td colspan="2" class="px-4 py-2" x-text="'₱' + totalAmount.toFixed(2)"></td>
              </tr>
            </tfoot>
          </table>
        </div>
      
        <!-- Payment Method -->
        <div>
          <label for="paymentMethod" class="block mb-1 font-semibold">Select Payment Method</label>
          <select id="paymentMethod" x-model="paymentMethod" class="border rounded px-3 py-2 w-full">
            <option value="" disabled>Select Method</option>
            <option value="Cash">Cash</option>
            <option value="Check">Check</option>
            <option value="Bank Transfer">Bank Transfer</option>
          </select>
        </div>
      
        <!-- Enter Amount -->
        <div>
          <label for="amount" class="block mb-1 font-semibold">Enter Amount</label>
          <input id="amount" type="number" min="0" step="0.01" x-model.number="amount" class="border rounded px-3 py-2 w-full" />
        </div>
      
        <!-- Other Deduction (Optional) -->
        <div>
          <label for="deduction" class="block mb-1 font-semibold">Other Deduction (optional)</label>
          <input id="deduction" type="number" min="0" step="0.01" x-model.number="deduction" placeholder="₱0.00" class="border rounded px-3 py-2 w-full" />
        </div>
      
        <!-- Remarks -->
        <div>
          <label for="remarks" class="block mb-1 font-semibold">Remarks</label>
          <textarea id="remarks" x-model="remarks" rows="3" class="border rounded px-3 py-2 w-full" placeholder="Add any notes here..."></textarea>
        </div>
      
        <!-- Conditional Fields based on Payment Method -->
        <template x-if="paymentMethod === 'Check'">
          <div class="space-y-4">
            <div>
              <label for="checkBank" class="block mb-1 font-semibold">Select Bank</label>
              <select id="checkBank" x-model="checkBank" class="border rounded px-3 py-2 w-full">
                <option value="" disabled>Select Bank</option>
                <option>Bank A</option>
                <option>Bank B</option>
                <option>Bank C</option>
              </select>
            </div>
            <div>
              <label for="chequeNumber" class="block mb-1 font-semibold">Cheque Number</label>
              <input id="chequeNumber" type="text" x-model="chequeNumber" class="border rounded px-3 py-2 w-full" />
            </div>
            <div>
              <label for="checkDate" class="block mb-1 font-semibold">Check Date</label>
              <input id="checkDate" type="date" x-model="checkDate" class="border rounded px-3 py-2 w-full" />
            </div>
          </div>
        </template>
      
        <template x-if="paymentMethod === 'Bank Transfer'">
          <div class="space-y-4">
            <div>
              <label for="transferBank" class="block mb-1 font-semibold">Select Bank</label>
              <select id="transferBank" x-model="transferBank" class="border rounded px-3 py-2 w-full">
                <option value="" disabled>Select Bank</option>
                <option>Bank A</option>
                <option>Bank B</option>
                <option>Bank C</option>
              </select>
            </div>
            <div>
              <label for="referenceNumber" class="block mb-1 font-semibold">Reference Number</label>
              <input id="referenceNumber" type="text" x-model="referenceNumber" class="border rounded px-3 py-2 w-full" />
            </div>
            <div>
              <label for="transactionDate" class="block mb-1 font-semibold">Transaction Date</label>
              <input id="transactionDate" type="date" x-model="transactionDate" class="border rounded px-3 py-2 w-full" />
            </div>
          </div>
        </template>
      
        <!-- Save Button -->
        <div class="flex justify-end mt-6">
          <button @click="savePayment()" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save Payment</button>
        </div>
      
      </div>
      
      <script>
        function paymentApplication() {
          return {
            date: new Date().toISOString().slice(0, 10),
            customer: '',
            selectedInvoice: '',
            invoices: [
              { number: 'INV-001', date: '2025-06-01', amount: 1500.00 },
              { number: 'DR-002', date: '2025-06-03', amount: 1200.00 },
              { number: 'INV-003', date: '2025-06-05', amount: 800.00 },
            ],
            selectedInvoices: [],
            paymentMethod: '',
            amount: null,
            deduction: null,
            remarks: '',
            // Check fields
            checkBank: '',
            chequeNumber: '',
            checkDate: '',
            // Bank transfer fields
            transferBank: '',
            referenceNumber: '',
            transactionDate: '',
      
            addInvoice() {
              if (!this.selectedInvoice) return;
              const invoiceExists = this.selectedInvoices.find(inv => inv.number === this.selectedInvoice);
              if (!invoiceExists) {
                const invoice = this.invoices.find(inv => inv.number === this.selectedInvoice);
                if (invoice) this.selectedInvoices.push(invoice);
              }
              this.selectedInvoice = '';
            },
      
            removeInvoice(index) {
              this.selectedInvoices.splice(index, 1);
            },
      
            get totalAmount() {
              return this.selectedInvoices.reduce((sum, inv) => sum + inv.amount, 0);
            },
      
            savePayment() {
              // Simple validation example
              if (!this.date || !this.customer || this.selectedInvoices.length === 0 || !this.paymentMethod || !this.amount) {
                alert('Please fill in all required fields.');
                return;
              }
      
              // You can add more validations based on payment method here
      
              const paymentData = {
                date: this.date,
                customer: this.customer,
                invoices: this.selectedInvoices,
                paymentMethod: this.paymentMethod,
                amount: this.amount,
                deduction: this.deduction,
                remarks: this.remarks,
                checkDetails: this.paymentMethod === 'Check' ? {
                  bank: this.checkBank,
                  chequeNumber: this.chequeNumber,
                  checkDate: this.checkDate,
                } : null,
                bankTransferDetails: this.paymentMethod === 'Bank Transfer' ? {
                  bank: this.transferBank,
                  referenceNumber: this.referenceNumber,
                  transactionDate: this.transactionDate,
                } : null,
              };
      
              console.log('Saving payment:', paymentData);
              alert('Payment saved! Check console for data.');
      
              // Clear form (optional)
              this.customer = '';
              this.selectedInvoices = [];
              this.paymentMethod = '';
              this.amount = null;
              this.deduction = null;
              this.remarks = '';
              this.checkBank = '';
              this.chequeNumber = '';
              this.checkDate = '';
              this.transferBank = '';
              this.referenceNumber = '';
              this.transactionDate = '';
            }
          }
        }
      </script>
      
</div>
