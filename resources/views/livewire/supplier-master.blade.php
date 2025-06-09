<div class="m-5" x-data="SupplierTable">
    <!-- Header with title and button group --> 
    <h2 class="text-2xl font-semibold text-gray-900">Supplier Master</h2>
    <div class="flex items-center justify-between mb-1 mt-5">
        <!-- 🔍 Search Bar -->
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
      
        <!--  Button Group -->
        <div class="flex items-center gap-2">
          <!-- Add Button -->
          <x-button emerald right-icon="plus" x-on:click="$openModal('Add')" />
          @include('partials.supplier-modal.supplier-edit')
            
        
        
        
      
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
          @include('partials.supplier-modal.supplier-edit')
      
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
                @include('partials.supplier-modal.supplier-delete')

        </div>
      </div>
      
  
<!-- Table -->
<div class="overflow-hidden rounded-lg border border-gray-200 shadow-md" x-data="customerTable">
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
          <th class="px-6 py-4 font-medium text-gray-900">Customer Name</th>
          <th class="px-6 py-4 font-medium text-gray-900">Customer Address</th>
          <th class="px-6 py-4 font-medium text-gray-900">Terms (No. of Days)</th>
          <th class="px-6 py-4 font-medium text-gray-900">Customer Contact Number</th>
          <th class="px-6 py-4 font-medium text-gray-900">Customer Contact Person</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 border-t border-gray-100">
        <template x-for="customer in customers" :key="customer.id">
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-4">
              <input
                type="checkbox"
                :value="customer.id"
                x-model="selected"
                class="h-4 w-4 text-blue-600"
              />
            </td>
            <td class="px-6 py-4" x-text="customer.name"></td>
            <td class="px-6 py-4" x-text="customer.address"></td>
            <td class="px-6 py-4" x-text="customer.term"></td>
            <td class="px-6 py-4" x-text="customer.contact"></td>
            <td class="px-6 py-4" x-text="customer.contact_person"></td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
  
  <script>
    document.addEventListener("alpine:init", () => {
      Alpine.data("SupplierTable", () => ({
        selected: [],
        customers: [
          {
            id: 1,
            name: "John Doe",
            address: "123 Main St, Cityville",
            term: 30,
            contact: "09171234567",
            contact_person: "Jane Doe"
          },
          {
            id: 2,
            name: "Acme Vet Clinic",
            address: "456 Clinic Rd, Vet Town",
            term: 15,
            contact: "09987654321",
            contact_person: "Dr. Smith"
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
  