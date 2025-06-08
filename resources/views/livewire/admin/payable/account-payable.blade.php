<div>
    <h2 class="text-2xl font-semibold text-gray-900">Account Payable </h2>
    <div x-data="payablePayment()" class="p-4 space-y-6">

        <!-- Table showing existing payments -->
        <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full">
          <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
            <thead class="bg-gray-50 sticky top-0 z-10">
              <tr>
                <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                <th class="px-6 py-4 font-medium text-gray-900">Supplier</th>
                <th class="px-6 py-4 font-medium text-gray-900">Payment Method</th>
                <th class="px-6 py-4 font-medium text-gray-900">Net Amount</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 border-t border-gray-100">
              <template x-for="payment in payments" :key="payment.id">
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4" x-text="payment.date"></td>
                  <td class="px-6 py-4" x-text="payment.supplier"></td>
                  <td class="px-6 py-4" x-text="payment.method"></td>
                  <td class="px-6 py-4" x-text="formatCurrency(payment.netAmount)"></td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>
      
        <!-- Button to open modal -->
        <div>
          <button
            @click="openModal()"
            class="bg-blue-600 text-white px-4 py-2 rounded-md font-semibold hover:bg-blue-700"
          >
            Add Payment to Supplier
          </button>
        </div>
      
        <!-- Modal -->
        <div
        x-show="modalOpen"
        x-transition
        class="fixed inset-0  bg-opacity-50 flex items-center justify-center z-50"
      >
      
          <div
            @click.away="closeModal()"
            class="bg-white rounded-lg shadow-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto p-6"
          >
            <h2 class="text-xl font-bold mb-4">Add Payment to Supplier</h2>
      
            <form @submit.prevent="savePayment()">
      
              <!-- Select Date -->
              <div class="mb-4">
                <label class="block font-semibold mb-1" for="date">Select Date</label>
                <input
                  type="date"
                  id="date"
                  x-model="form.date"
                  class="w-full border border-gray-300 rounded-md px-3 py-2"
                />
              </div>
      
              <!-- Select Supplier -->
              <div class="mb-4">
                <label class="block font-semibold mb-1" for="supplier">Select Supplier</label>
                <select
                  id="supplier"
                  x-model="form.supplier"
                  class="w-full border border-gray-300 rounded-md px-3 py-2"
                  required
                >
                  <option value="" disabled>Select Supplier</option>
                  <template x-for="sup in suppliers" :key="sup.id">
                    <option :value="sup.name" x-text="sup.name"></option>
                  </template>
                </select>
              </div>
      
              <!-- Select Payment Method -->
              <div class="mb-4">
                <label class="block font-semibold mb-1" for="paymentMethod">Select Payment Method</label>
                <select
                  id="paymentMethod"
                  x-model="form.paymentMethod"
                  class="w-full border border-gray-300 rounded-md px-3 py-2"
                  required
                >
                  <option value="" disabled>Select Payment Method</option>
                  <option value="Cash">Cash</option>
                  <option value="Cheque">Cheque</option>
                  <option value="Bank Transfer">Bank Transfer</option>
                </select>
              </div>
      
              <!-- Conditional Fields for Cheque -->
              <template x-if="form.paymentMethod === 'Cheque'">
                <div class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <div>
                    <label class="block font-semibold mb-1" for="chequeNumber">Cheque Number</label>
                    <input
                      type="text"
                      id="chequeNumber"
                      x-model="form.chequeNumber"
                      class="w-full border border-gray-300 rounded-md px-3 py-2"
                      required
                    />
                  </div>
                  <div>
                    <label class="block font-semibold mb-1" for="chequeDate">Cheque Date</label>
                    <input
                      type="date"
                      id="chequeDate"
                      x-model="form.chequeDate"
                      class="w-full border border-gray-300 rounded-md px-3 py-2"
                      required
                    />
                  </div>
                </div>
              </template>
      
              <!-- Conditional Fields for Bank Transfer -->
              <template x-if="form.paymentMethod === 'Bank Transfer'">
                <div class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                  <div>
                    <label class="block font-semibold mb-1" for="bank">Select Bank</label>
                    <select
                      id="bank"
                      x-model="form.bank"
                      class="w-full border border-gray-300 rounded-md px-3 py-2"
                      required
                    >
                      <option value="" disabled>Select Bank</option>
                      <template x-for="bank in banks" :key="bank.id">
                        <option :value="bank.name" x-text="bank.name"></option>
                      </template>
                    </select>
                  </div>
                  <div>
                    <label class="block font-semibold mb-1" for="referenceNumber">Reference Number</label>
                    <input
                      type="text"
                      id="referenceNumber"
                      x-model="form.referenceNumber"
                      class="w-full border border-gray-300 rounded-md px-3 py-2"
                      required
                    />
                  </div>
                  <div>
                    <label class="block font-semibold mb-1" for="transactionDate">Transaction Date</label>
                    <input
                      type="date"
                      id="transactionDate"
                      x-model="form.transactionDate"
                      class="w-full border border-gray-300 rounded-md px-3 py-2"
                      required
                    />
                  </div>
                </div>
              </template>
      
              <!-- Invoice/DR Table -->
              <div class="overflow-auto rounded-lg border border-gray-300 mb-4 max-h-56">
                <table class="min-w-full text-sm text-gray-600">
                  <thead class="bg-gray-100 sticky top-0 z-10">
                    <tr>
                      <th class="px-4 py-2 text-left font-semibold">Invoice</th>
                      <th class="px-4 py-2 text-left font-semibold">Invoice Date</th>
                      <th class="px-4 py-2 text-right font-semibold">Gross Amount</th>
                      <th class="px-4 py-2 text-right font-semibold">Withholding Tax %</th>
                      <th class="px-4 py-2 text-right font-semibold">Withholding Amount</th>
                      <th class="px-4 py-2 text-right font-semibold">Net Amount</th>
                      <th class="px-4 py-2"></th>
                    </tr>
                  </thead>
                  <tbody>
                    <template x-for="(invoice, index) in form.invoices" :key="invoice.id">
                      <tr class="border-t border-gray-200 hover:bg-gray-50">
                        <td class="px-4 py-2" x-text="invoice.number"></td>
                        <td class="px-4 py-2" x-text="invoice.date"></td>
                        <td class="px-4 py-2 text-right" x-text="formatCurrency(invoice.grossAmount)"></td>
                        <td class="px-4 py-2 text-right">
                          <input
                            type="number"
                            min="0"
                            max="100"
                            step="0.01"
                            class="w-16 border border-gray-300 rounded-md px-1 py-0.5 text-right"
                            x-model.number="invoice.withholdingTaxPercent"
                            @input="calculateWithholding(index)"
                          />
                        </td>
                        <td class="px-4 py-2 text-right" x-text="formatCurrency(invoice.withholdingAmount)"></td>
                        <td class="px-4 py-2 text-right" x-text="formatCurrency(invoice.netAmount)"></td>
                        <td class="px-4 py-2 text-center">
                          <button
                            type="button"
                            class="text-red-600 hover:text-red-800 font-semibold"
                            @click="removeInvoice(index)"
                          >Remove</button>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                  <tfoot class="bg-gray-100 font-semibold">
                    <tr>
                      <td colspan="2" class="px-4 py-2 text-right">Total Net Amount:</td>
                      <td colspan="4" class="px-4 py-2 text-right" x-text="formatCurrency(totalNetAmount())"></td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
      
              <!-- Button to add new invoice line -->
              <div class="mb-4">
                <button
                  type="button"
                  @click="addInvoice()"
                  class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700"
                >
                  + Add Invoice/DR
                </button>
              </div>
      
              <!-- Remarks -->
              <div class="mb-4">
                <label class="block font-semibold mb-1" for="remarks">Remarks</label>
                <textarea
                  id="remarks"
                  x-model="form.remarks"
                  rows="3"
                  class="w-full border border-gray-300 rounded-md px-3 py-2 resize-y"
                  placeholder="Optional notes..."
                ></textarea>
              </div>
      
              <!-- Modal Footer Buttons -->
              <div class="flex justify-end gap-4 mt-6">
                <button
                  type="button"
                  @click="closeModal()"
                  class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700"
                >
                  Save Payment
                </button>
              </div>
      
            </form>
          </div>
        </div>
      
      </div>
      
      <script>
        function payablePayment() {
          return {
            modalOpen: false,
            payments: [
              // Example data, replace with your real data
              { id: 1, date: '2025-06-05', supplier: 'Supplier A', method: 'Cash', netAmount: 15000 },
              { id: 2, date: '2025-06-06', supplier: 'Supplier B', method: 'Cheque', netAmount: 12000 },
            ],
            suppliers: [
              { id: 1, name: 'Supplier A' },
              { id: 2, name: 'Supplier B' },
              { id: 3, name: 'Supplier C' },
            ],
            banks: [
              { id: 1, name: 'Bank A' },
              { id: 2, name: 'Bank B' },
            ],
            form: {
              date: new Date().toISOString().substr(0, 10),
              supplier: '',
              paymentMethod: '',
              chequeNumber: '',
              chequeDate: '',
              bank: '',
              referenceNumber: '',
              transactionDate: '',
              invoices: [],
              remarks: '',
            },
      
            openModal() {
              this.modalOpen = true;
              // Reset form
              this.form = {
                date: new Date().toISOString().substr(0, 10),
                supplier: '',
                paymentMethod: '',
                chequeNumber: '',
                chequeDate: '',
                bank: '',
                referenceNumber: '',
                transactionDate: '',
                invoices: [],
                remarks: '',
              };
            },
      
            closeModal() {
              this.modalOpen = false;
            },
      
            addInvoice() {
              // Add empty invoice line with unique id
              this.form.invoices.push({
                id: Date.now() + Math.random(),
                number: '',
                date: '',
                grossAmount: 0,
                withholdingTaxPercent: 0,
                withholdingAmount: 0,
                netAmount: 0,
              });
            },
      
            removeInvoice(index) {
              this.form.invoices.splice(index, 1);
            },
      
            calculateWithholding(index) {
              let inv = this.form.invoices[index];
              inv.withholdingAmount = (inv.grossAmount * inv.withholdingTaxPercent) / 100 || 0;
              inv.netAmount = inv.grossAmount - inv.withholdingAmount;
            },
      
            totalNetAmount() {
              return this.form.invoices.reduce((sum, inv) => sum + (inv.netAmount || 0), 0);
            },
      
            formatCurrency(value) {
              return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value || 0);
            },
      
            savePayment() {
              // Basic validation example
              if (!this.form.supplier || !this.form.paymentMethod) {
                alert('Please fill in all required fields.');
                return;
              }
              if (this.form.paymentMethod === 'Cheque' && (!this.form.chequeNumber || !this.form.chequeDate)) {
                alert('Please fill cheque number and date.');
                return;
              }
              if (this.form.paymentMethod === 'Bank Transfer' && (!this.form.bank || !this.form.referenceNumber || !this.form.transactionDate)) {
                alert('Please fill all bank transfer details.');
                return;
              }
              if (this.form.invoices.length === 0) {
                alert('Please add at least one invoice.');
                return;
              }
      
              // Save payment (for demo, just add to payments array)
              this.payments.push({
                id: Date.now(),
                date: this.form.date,
                supplier: this.form.supplier,
                method: this.form.paymentMethod,
                netAmount: this.totalNetAmount(),
              });
      
              this.closeModal();
            },
          }
        }
      </script>
      
</div>
