<div>
    <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4" x-data="cashFlow()">
        <h2 class="text-xl font-semibold mb-4">Expenses</h2>
      
        <!-- Add Entry Form -->
        <form @submit.prevent="addEntry" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Date Field -->
            <div class="form-control">
              <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
              <input type="date" id="date" name="date" class="input input-bordered w-full" x-model="newEntry.date">
            </div>
          
            <!-- Category Field -->
            <div class="form-control">
              <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
              <input type="text" x-model="newEntry.category" class="input input-bordered w-full" placeholder="Category">
            </div>
          
            <!-- Payee Field -->
            <div class="form-control">
              <label class="block text-sm font-medium text-gray-700 mb-1">Payee</label>
              <input type="text" x-model="newEntry.payee" class="input input-bordered w-full" placeholder="Payee">
            </div>
          
            <!-- Description Field -->
            <div class="form-control">
              <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
              <input type="text" x-model="newEntry.description" class="input input-bordered w-full" placeholder="Description">
            </div>
          
            <!-- Amount Field -->
            <div class="form-control">
              <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
              <input type="number" step="0.01" x-model="newEntry.amount" class="input input-bordered w-full" placeholder="Amount">
            </div>
          
            <!-- Paid By Field -->
            <div class="form-control">
              <label class="block text-sm font-medium text-gray-700 mb-1">Paid by</label>
              <input type="text" x-model="newEntry.paid_by" class="input input-bordered w-full" placeholder="Paid by">
            </div>
          
            <!-- Remarks Field -->
            <div class="form-control md:col-span-3">
              <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
              <input type="text" x-model="newEntry.remarks" class="input input-bordered w-[390px]" placeholder="Remarks">
            </div>
          
            <!-- Submit Button -->
            <div class="md:col-span-3 text-center">
              <x-button info label="Add Entry" type="submit" class="!w-94" />
            </div>
          </form>
          
          <hr>
      
        <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
            <thead class="bg-gray-50 sticky top-0 z-10">
              <tr>
                <th class="px-4 py-2 font-medium text-gray-900">Date</th>
                <th class="px-4 py-2 font-medium text-gray-900">Category</th>
                <th class="px-4 py-2 font-medium text-gray-900">Payee</th>
                <th class="px-4 py-2 font-medium text-gray-900">Description</th>
                <th class="px-4 py-2 font-medium text-gray-900">Amount</th>
                <th class="px-4 py-2 font-medium text-gray-900">Paid by</th>
                <th class="px-4 py-2 font-medium text-gray-900">Remarks</th>
                <th class="px-4 py-2 font-medium text-gray-900 text-center">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 border-t border-gray-100">
              <template x-for="(entry, index) in entries" :key="index">
                <tr class="hover:bg-gray-50">
                  <td class="px-4 py-2" x-text="entry.date"></td>
                  <td class="px-4 py-2" x-text="entry.category"></td>
                  <td class="px-4 py-2" x-text="entry.payee"></td>
                  <td class="px-4 py-2" x-text="entry.description"></td>
                  <td class="px-4 py-2" x-text="formatCurrency(entry.amount)"></td>
                  <td class="px-4 py-2" x-text="entry.paid_by"></td>
                  <td class="px-4 py-2" x-text="entry.remarks"></td>
                  <td class="px-4 py-2 text-center">
                    <x-button emerald label="Edit" @click="editEntry(index)" />
                  </td>
                </tr>
              </template>
              <tr x-show="entries.length === 0">
                <td colspan="8" class="px-4 py-4 text-center text-gray-500">No entries available.</td>
              </tr>
            </tbody>
          </table>
          
      </div>
      
      <script>
        function cashFlow() {
          return {
            entries: [
              {
                date: '2025-06-01',
                category: 'Supplies',
                payee: 'Pet Supplier Co.',
                description: 'Purchased pet food',
                amount: 1200,
                paid_by: 'Cash',
                remarks: 'Monthly stock',
              },
              {
                date: '2025-06-02',
                category: 'Service',
                payee: 'VetClinic',
                description: 'Vaccination fee',
                amount: 800,
                paid_by: 'Credit',
                remarks: 'Rabies shot',
              },
            ],
            newEntry: {
              date: '',
              category: '',
              payee: '',
              description: '',
              amount: '',
              paid_by: '',
              remarks: '',
            },
            formatCurrency(value) {
              return '₱' + parseFloat(value).toFixed(2);
            },
            addEntry() {
              if (this.newEntry.date && this.newEntry.category && this.newEntry.amount) {
                this.entries.push({ ...this.newEntry });
                this.newEntry = {
                  date: '',
                  category: '',
                  payee: '',
                  description: '',
                  amount: '',
                  paid_by: '',
                  remarks: '',
                };
              } else {
                alert('Please fill in the required fields: Date, Category, and Amount.');
              }
            },
          }
        }
      </script>
      
</div>
