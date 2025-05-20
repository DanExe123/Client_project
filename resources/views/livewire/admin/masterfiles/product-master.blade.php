<div class="m-5" x-data="ProductTable">
    <!-- Header with title and button group --> 
    <h2 class="text-2xl font-semibold text-gray-900">Product Master</h2>
    <div class="flex items-center justify-between mb-1 mt-5">
        <!-- 🔍 Search Bar -->
        <div class="w-full max-w-xs">
          <input
            type="text"
            x-model="search"
            placeholder="Search by Barcode or Product Description..."
            class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          />
        </div>
      
        <!--  Button Group -->
        <div class="flex items-center gap-2">
          <!-- Add Button -->
          <x-button emerald right-icon="plus" x-on:click="$openModal('Add')" />
          @include('partials.product-modal.product-master-modal')
      
          <!-- Edit Button -->
          <x-button
          
            right-icon="pencil"
            interaction="positive"
            x-bind:class="selected.length === 0 
              ? 'bg-gray-300 text-white cursor-not-allowed' 
              : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'"
            x-bind:disabled="selected.length === 0"
            x-on:click="$openModal('Edit')">
          </x-button>
          @include('partials.product-modal.product-edit')
      
          <!-- Delete Button -->
          <x-button
            right-icon="trash"
            interaction="negative"
            x-bind:class="selected.length === 0 
              ? 'bg-red-300 text-white cursor-not-allowed' 
              : 'bg-red-600 hover:bg-red-700 text-white'"
              x-bind:disabled="selected.length === 0"
              x-on:click="$openModal('Delete')">
            </x-button>
                  @include('partials.product-modal.product-delete')      

        </div>
      </div>
      
  
  <!-- Table -->
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-md" x-data="productTable">
    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-4">
            <input
              type="checkbox"
              @change="toggleAll"
              :checked="isAllSelected"
              class="h-4 w-4 text-blue-600"
            />
          </th>
          <th class="px-6 py-4 font-medium text-gray-900">Barcode</th>
          <th class="px-6 py-4 font-medium text-gray-900">Supplier</th>
          <th class="px-6 py-4 font-medium text-gray-900">Product Description</th>
          <th class="px-6 py-4 font-medium text-gray-900">Highest Unit of Measurement</th>
          <th class="px-6 py-4 font-medium text-gray-900">Lowest Unit of Measurement</th>
          <th class="px-6 py-4 font-medium text-gray-900">Price</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 border-t border-gray-100">
        <template x-for="product in products" :key="product.id">
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-4">
              <input
                type="checkbox"
                :value="product.id"
                x-model="selected"
                class="h-4 w-4 text-blue-600"
              />
            </td>
            <td class="px-6 py-4" x-text="product.barcode"></td>
            <td class="px-6 py-4" x-text="product.supplier"></td>
            <td class="px-6 py-4" x-text="product.description"></td>
            <td class="px-6 py-4" x-text="product.highest_uom"></td>
            <td class="px-6 py-4" x-text="product.lowest_uom"></td>
            <td class="px-6 py-4" x-text="formatPrice(product.price)"></td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>  
  </div>
  
  <script>
    document.addEventListener("alpine:init", () => {
      Alpine.data("ProductTable", () => ({
        selected: [],
        products: [
        {
          id: 1,
          barcode: "1234567890123",
          supplier: "Supplier A",
          description: "Vitamin C 500mg Tablet",
          highest_uom: "Box",
          lowest_uom: "Tablet",
          price: 150.0,
        },
        {
          id: 2,
          barcode: "9876543210987",
          supplier: "Supplier B",
          description: "Pet Shampoo - Anti Tick",
          highest_uom: "Gallon",
          lowest_uom: "Bottle",
          price: 450.5,
        },
        ],
      get isAllSelected() {
        return this.selected.length === this.products.length;
      },
      toggleAll() {
        if (this.isAllSelected) {
          this.selected = [];
        } else {
          this.selected = this.products.map((p) => p.id);
        }
      },
      formatPrice(value) {
        return "₱" + parseFloat(value).toFixed(2);
      },
    }));
  });
  </script>
  