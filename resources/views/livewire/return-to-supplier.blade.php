<div>
  <div x-cloak x-data="returnToSupplierUI" class="space-y-2">
    <h2 class="text-2xl font-semibold text-gray-900">Return To Supplier</h2>
    <div class="flex gap-2 justify-end">
      <div class="w-full sm:max-w-xs flex justify-start relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
          <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
        </span>
        <input type="text" x-model="search" placeholder="Search..."
          class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
      </div>
      <x-button right-icon="pencil" interaction="positive" x-bind:class="selectedReturns.length === 0
                ? 'bg-gray-300 text-white cursor-not-allowed'
                : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'" x-bind:disabled="selectedReturns.length === 0"
        x-on:click="$openModal('Edit')">
      </x-button>

      <x-button right-icon="trash" interaction="negative" x-bind:class="selectedReturns.length === 0
                ? 'bg-red-300 text-white cursor-not-allowed'
                : 'bg-red-600 hover:bg-red-700 text-white'" x-bind:disabled="selectedReturns.length === 0"
        x-on:click="$openModal('Delete')">
      </x-button>
    </div>

    <div class="overflow-auto rounded-lg border border-gray-200">
      <table class="min-w-[800px] w-full border-collapse bg-white text-left text-sm text-gray-500">
        <thead class="bg-gray-50 sticky top-0 z-10">
          <tr>
            <th class="px-4 py-4">
              <input type="checkbox" @change="toggleAllReturns" :checked="areAllReturnsSelected"
                class="h-4 w-4 text-blue-600" />
            </th>
            <th class="px-6 py-4 font-medium text-gray-900">Date</th>
            <th class="px-6 py-4 font-medium text-gray-900">Supplier</th>
            <th class="px-6 py-4 font-medium text-gray-900">Total</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
          <template x-for="(rs, index) in returnList" :key="index">
            <tr class="hover:bg-gray-50">
              <td class="px-4 py-4">
                <input type="checkbox" :value="index" x-model="selectedReturns" class="h-4 w-4 text-blue-600" />
              </td>
              <td class="px-6 py-4" x-text="rs.date"></td>
              <td class="px-6 py-4" x-text="rs.supplier"></td>
              <td class="px-6 py-4"
                x-text="formatPrice(rs.products.reduce((total, p) => total + (p.quantity * p.unitPrice), 0))">
              </td>
            </tr>
          </template>
          <tr x-show="returnList.length === 0">
            <td colspan="4" class="text-center p-4 text-gray-500">No return records yet.</td>
          </tr>
        </tbody>
      </table>
    </div>

    <hr>
    <div x-show="showForm" x-transition class="bg-white border border-gray-200 rounded-lg p-6 space-y-4 mt-5">
      <div class="text-lg font-semibold" x-text="formTitle"></div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div x-data="{
                    currentDate: '', // Property to hold the date string in YYYY-MM-DD format
                    init() {
                        // Get today's date
                        const today = new Date();
                        const year = today.getFullYear();
                        const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                        const day = String(today.getDate()).padStart(2, '0');

                        // Format it as YYYY-MM-DD
                        this.currentDate = `${year}-${month}-${day}`;
                        // Set the form date to today's date if it's new
                        if (!this.form.date) {
                            this.form.date = this.currentDate;
                        }
                    }
                }">
          <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
          <input type="date" id="date" name="date" class="w-full mt-1 border rounded px-3 py-2" x-model="form.date">
        </div>
        <div>
          <label for="supplier" class="block text-sm font-medium text-gray-700">Supplier</label>
          <select id="supplier" name="supplier" x-model="form.supplier_id" class="w-full mt-1 border rounded px-3 py-2">
            <option value="" disabled selected>Select a Supplier</option>
            @foreach ($suppliers as $supplier)
        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
      @endforeach
          </select>
        </div>
      </div>

      <h4 class="text-md font-semibold text-gray-700 mt-4">Products to Return</h4>
      <div class="overflow-auto mt-4">
        <table class="w-full text-sm border">
          <thead class="bg-gray-100">
            <tr>
              <th class="p-2">Barcode</th>
              <th class="p-2">Product Description</th>
              <th class="p-2">Qty</th>
              <th class="p-2">Unit Price</th>
              <th class="p-2">SubTotal</th>
              <th class="p-2">Action</th>
            </tr>
          </thead>
          <tbody>
            <template x-for="(item, index) in form.products" :key="index">
              <tr>
                <td class="p-2">
                  {{-- Barcode input (often readonly, populated when product_id is selected) --}}
                  <input type="text" x-model="item.barcode" class="w-full border px-2 py-1 rounded bg-gray-100"
                    placeholder="Scan or enter barcode" />
                </td>
                <td class="p-2">
                  <select x-model="item.product_id" @change="updateProductDetails(index)"
                    class="w-[250px] border px-2 py-1 rounded">
                    <option value="">Select</option>
                    {{-- Make sure $products is passed from your Livewire/PHP component --}}
                    @foreach ($products as $product)
            <option value="{{ $product->id }}">{{ $product->description }}</option>
          @endforeach
                  </select>
                </td>
                <td class="p-2">
                  <input type="number" x-model="item.quantity" @input="updateTotal(index)" min="1"
                    class="w-[250px] border px-2 py-1 rounded" />
                </td>
                <td class="p-2">
                  {{-- Unit Price input (often readonly, fetched from product) --}}
                  <input type="number" x-model="item.unitPrice" step="0.01" readonly
                    class="w-full border px-2 py-1 rounded bg-gray-100" />
                </td>
                <td class="p-2 text-right" x-text="formatPrice(item.quantity * item.unitPrice)"></td>
                <td class="p-2 text-center">
                  <x-button red label="Remove" @click="removeProduct(index)" />
                </td>
              </tr>
            </template>
            <template x-if="form.products.length === 0">
              <tr>
                <td colspan="6" class="p-4 text-center text-gray-500">No products added. Click "Add Product" to start.
                </td>
              </tr>
            </template>
          </tbody>
          <tfoot class="bg-gray-50">
            <tr>
              <td colspan="4" class="p-2 text-right font-semibold">Grand Total:</td>
              <td class="p-2 font-semibold text-right" x-text="formatPrice(grandTotal)"></td>
              <td class="p-2"></td> {{-- Empty for action column --}}
            </tr>
          </tfoot>
        </table>
        <x-button green label="Add Product" class="mt-2 ml-2 mb-1" @click="addProduct()" />
      </div>

      <div class="pt-4">
        <x-textarea name="remarks" label="Remarks" placeholder="Write any remarks for this return"
          x-model="form.remarks" />
      </div>

      <div class="flex justify-end gap-2">
        <x-button blue label="Save Return" @click="saveReturn()" />
        <x-button secondary label="Cancel" @click="showForm = false; resetForm()" />
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('returnToSupplierUI', () => ({
        // Properties to be passed from PHP via Livewire, or hardcoded for example
        // In a Livewire component, you'd fetch these in `mount()` and pass them to the view.
        allProducts: @json($products ?? []), // Ensure $products is passed to the view
        suppliers: @json($suppliers ?? []),

        search: '',
        selectedReturns: [],
        returnList: [], // This will hold your list of return records

        showForm: true, // Set to true to show the form initially, false to hide
        formTitle: 'Add New Supplier Return',
        selectedIndex: null, // For editing functionality

        form: {
          date: '',
          supplier_id: '',
          products: [],
          remarks: '', // Added remarks to the form data
        },

        init() {
          // Initialize the form products with an empty row if creating a new return
          if (this.form.products.length === 0) {
            this.addProduct();
          }
          // Initialize current date on form
          const today = new Date();
          const year = today.getFullYear();
          const month = String(today.getMonth() + 1).padStart(2, '0');
          const day = String(today.getDate()).padStart(2, '0');
          this.form.date = `${year}-${month}-${day}`;
        },

        // Product Table Functions
        addProduct() {
          this.form.products.push({
            barcode: '',
            product_id: '',
            quantity: 1, // Changed from qty to quantity for consistency
            unitPrice: 0.00, // Changed from price to unitPrice
            subtotal: 0.00 // To store calculated subtotal for each item
          });
        },

        removeProduct(index) {
          this.form.products.splice(index, 1);
          this.updateGrandTotal(); // Recalculate grand total after removal
        },

        // Called when product dropdown changes or quantity/unit price changes
        updateProductDetails(index) {
          const productId = this.form.products[index].product_id;
          const product = this.allProducts.find(p => p.id == productId);

          if (product) {
            this.form.products[index].barcode = product.barcode;
            this.form.products[index].unitPrice = parseFloat(product.price); // Set price from product data
          } else {
            // Reset if no product is selected or found
            this.form.products[index].barcode = '';
            this.form.products[index].unitPrice = 0.00;
          }
          this.updateTotal(index); // Recalculate subtotal for this row
        },

        updateTotal(index) {
          const item = this.form.products[index];
          const quantity = parseFloat(item.quantity) || 0;
          const unitPrice = parseFloat(item.unitPrice) || 0;
          item.subtotal = quantity * unitPrice; // Update subtotal for the item
          this.updateGrandTotal(); // Recalculate grand total
        },

        get grandTotal() {
          return this.form.products.reduce((sum, item) => sum + (item.subtotal || 0), 0).toFixed(2);
        },

        // Form Actions
        saveReturn() {
          // Add validation here before saving
          if (!this.form.date || !this.form.supplier_id || this.form.products.length === 0) {
            alert('Please fill in all required fields and add at least one product.');
            return;
          }

          // For demonstration, pushing to local array.
          // In a real application, you'd send this.form data to your backend (e.g., via Livewire or Fetch API).
          // Example: Livewire.dispatch('saveReturnEvent', this.form);
          if (this.selectedIndex === null) {
            this.returnList.push(JSON.parse(JSON.stringify(this.form)));
          } else {
            this.returnList.splice(this.selectedIndex, 1, JSON.parse(JSON.stringify(this.form)));
          }
          this.showForm = false; // Hide form after saving
          this.resetForm(); // Reset form for next entry/clear
          alert('Return saved successfully!');
        },

        resetForm() {
          this.form = {
            date: new Date().toISOString().slice(0, 10), // Reset date to today
            supplier_id: '',
            products: [],
            remarks: ''
          };
          this.selectedIndex = null;
          this.formTitle = 'Add New Supplier Return';
          this.addProduct(); // Add an initial empty product row
        },

        // Other UI functions
        formatPrice(value) {
          return '₱' + (value || 0).toFixed(2);
        },

        get areAllReturnsSelected() {
          return this.returnList.length > 0 && this.selectedReturns.length === this.returnList.length;
        },

        toggleAllReturns() {
          if (this.selectedReturns.length === this.returnList.length) {
            this.selectedReturns = [];
          } else {
            this.selectedReturns = this.returnList.map((_, index) => index);
          }
        },

        // You might have edit/delete functions that set `showForm = true` and populate `this.form`
        // For example:
        editReturn(index) {
          this.selectedIndex = index;
          this.formTitle = 'Edit Supplier Return';
          this.form = JSON.parse(JSON.stringify(this.returnList[index])); // Deep copy
          this.showForm = true;
        }
      }));
    });
  </script>

</div>