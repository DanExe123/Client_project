<div x-cloak>
  <div x-cloak x-data="returnToSupplierUI" class="space-y-2">
      <!-- Title -->
      <h2 class="text-2xl font-semibold text-gray-900">Return to supplier</h2>
    <!-- Action Buttons -->
<div class="flex gap-2 justify-end">
    <!-- Search Bar -->
    <div class="w-full sm:max-w-xs flex justify-start relative">
      <span class="absolute inset-y-0 left-0 flex items-center pl-3">
          <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
      </span>
      <input
          type="text"
          x-model="search"
          placeholder="Search..."
          class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
      />
  </div>
  <!-- Edit Button -->
  <x-button
    right-icon="pencil"
    interaction="positive"
    x-bind:class="selectedReturns.length === 0 
      ? 'bg-gray-300 text-white cursor-not-allowed' 
      : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'"
    x-bind:disabled="selectedReturns.length === 0"
    x-on:click="$openModal('Edit')">
  </x-button>
 

  <!-- Delete Button -->
  <x-button
    right-icon="trash"
    interaction="negative"
    x-bind:class="selectedReturns.length === 0 
      ? 'bg-red-300 text-white cursor-not-allowed' 
      : 'bg-red-600 hover:bg-red-700 text-white'"
    x-bind:disabled="selectedReturns.length === 0"
    x-on:click="$openModal('Delete')">
  </x-button>
  
</div>

<div class="overflow-auto rounded-lg border border-gray-200">
  <table class="min-w-[1000px] w-full border-collapse bg-white text-left text-sm text-gray-500">
    <thead class="bg-gray-50 sticky top-0 z-10">
      <tr>
        <th class="px-4 py-4">
          <input type="checkbox" @change="toggleAllReturns" :checked="areAllReturnsSelected" class="h-4 w-4 text-blue-600" />
        </th>
        <th class="px-4 py-4 font-medium text-gray-900">RS ID</th>
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
          <td class="px-4 py-4" x-text="`RS-${index + 1}`"></td>
          <td class="px-6 py-4" x-text="rs.date"></td>
          <td class="px-6 py-4" x-text="rs.supplier"></td>
          <td class="px-6 py-4" x-text="formatPrice(rs.products.reduce((total, p) => total + (p.qty * p.unitPrice), 0))"></td>
        </tr>
      </template>
      <tr x-show="returnList.length === 0">
        <td colspan="5" class="text-center p-4 text-gray-500">No return records yet.</td>
      </tr>
    </tbody>
  </table>
</div>

<hr class="my-6">

<!-- Form Section -->
<div x-show="showForm" x-transition class="bg-white border border-gray-200 rounded-lg p-6 space-y-4 mt-5">
  <div class="text-lg font-semibold" x-text="formTitle || 'Add Return to Supplier'"></div>

  <!-- Form Fields -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium text-gray-700">Date</label>
      <input type="date" x-model="form.date" class="w-full mt-1 border rounded px-3 py-2" />
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Supplier</label>
      <select x-model="form.supplier" class="w-full mt-1 border rounded px-3 py-2">
        <option disabled value="">Choose...</option>
        <template x-for="supplier in suppliers" :key="supplier">
          <option x-text="supplier"></option>
        </template>
      </select>
    </div>
  </div>

  <!-- Product Table -->
  <div class="overflow-auto mt-4">
    <table class="w-full text-sm border">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2">Barcode</th>
          <th class="p-2">Product</th>
          <th class="p-2">Qty</th>
          <th class="p-2">Unit Price</th>
          <th class="p-2">Total</th>
          <th class="p-2"></th>
        </tr>
      </thead>
      <tbody>
        <template x-for="(item, index) in form.products" :key="index">
          <tr>
            <td class="p-2">
              <input type="text" x-model="item.barcode" class="w-full border px-2 py-1 rounded" />
            </td>
            <td class="p-2">
              <select x-model="item.product" class="w-full border px-2 py-1 rounded">
                <option disabled value="">Select</option>
                <template x-for="product in productList" :key="product">
                  <option x-text="product"></option>
                </template>
              </select>
            </td>
            <td class="p-2">
              <input type="number" x-model="item.qty" class="w-full border px-2 py-1 rounded" min="1" />
            </td>
            <td class="p-2">
              <input type="number" x-model="item.unitPrice" class="w-full border px-2 py-1 rounded" min="0" />
            </td>
            <td class="p-2 text-right" x-text="formatPrice(item.qty * item.unitPrice)"></td>
            <td class="p-2 text-center">
              <x-button red label="Remove" @click="form.products.splice(index, 1)" />
            </td>
          </tr>
        </template>
      </tbody>
    </table>
    <x-button green label="Add Product" class="mt-2 ml-2 mb-1" @click="form.products.push({ barcode: '', product: '', qty: 1, unitPrice: 0 })" />
  </div>

  <!-- Grand Total -->
  <div class="text-right font-semibold text-gray-700 mt-2">
    Grand Total: <span x-text="formatPrice(grandTotal)"></span>
  </div>

  <!-- Actions -->
  <div class="flex justify-end gap-2">
    <x-button green label="Save" />
  </div>
</div>

<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('returnToSupplierUI', () => ({
      showForm: true,
      formTitle: '',
      selectedIndex: null,
      suppliers: ['Supplier A', 'Supplier B'],
      productList: ['Product X', 'Product Y'],
      returnList: [
        {
          date: '2025-06-01',
          supplier: 'Supplier A',
          products: [
            { name: 'Product X', qty: 3, unitPrice: 100 },
            { name: 'Product Y', qty: 2, unitPrice: 150 }
          ]
        },
        {
          date: '2025-06-02',
          supplier: 'Supplier B',
          products: [
            { name: 'Product X', qty: 1, unitPrice: 100 },
            { name: 'Product Y', qty: 5, unitPrice: 150 }
          ]
        }
      ],
      selectedReturns: [],
      form: {
        date: '',
        supplier: '',
        products: []
      },
      get grandTotal() {
        return this.form.products.reduce((total, p) => total + (p.qty * p.unitPrice), 0);
      },
      formatPrice(value) {
        return '₱' + Number(value).toFixed(2);
      },
      toggleAllReturns(event) {
        if (event.target.checked) {
          this.selectedReturns = this.returnList.map((_, i) => i);
        } else {
          this.selectedReturns = [];
        }
      },
      get areAllReturnsSelected() {
        return this.selectedReturns.length === this.returnList.length;
      }
    }));
  });
</script>

  
</div>