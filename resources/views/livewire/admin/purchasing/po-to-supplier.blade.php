<div class="grid grid-cols-3 gap-4 m-5" x-data="SupplierTable">
    <!-- Left side: Supplier Master table (col-span-2) -->
    <div class="col-span-2">
      <!-- Header and Button Group -->
      <h2 class="text-2xl font-semibold text-gray-900">Supplier Master</h2>
      <div class="flex items-center justify-between mb-1 mt-5">
        <!-- Search Bar -->
        <div class="w-full max-w-xs">
          <input
            type="text"
            x-model="search"
            placeholder="Search..."
            class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
          />
        </div>
        <!-- Button Group -->
        <div class="flex items-center gap-2">
          <x-button emerald right-icon="plus" x-on:click="$openModal('Add')" />
          @include('partials.supplier-modal.supplier-master-create')
  
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
  
      <!-- Main Table -->
      <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md">
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
    </div>
  
    <!-- Right side: Another table (col-span-1) -->
    <div class="bg-white p-4 rounded-lg border shadow-md mt-12">
      <h3 class="text-lg font-bold text-gray-800 mb-4">Other Table</h3>
      <table class="w-full text-sm text-left text-gray-600">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="px-4 py-2">ID</th>
            <th class="px-4 py-2">Label</th>
          </tr>
        </thead>
        <tbody>
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2">1</td>
            <td class="px-4 py-2">Sample A</td>
          </tr>
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-2">2</td>
            <td class="px-4 py-2">Sample B</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    get isAllSelected() {
  return this.selected.length === this.customers.length;
},
toggleAll() {
  if (this.isAllSelected) {
    this.selected = [];
  } else {
    this.selected = this.customers.map((c) => c.id);
  }
},

  </script>