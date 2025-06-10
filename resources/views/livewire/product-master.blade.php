<div class="m-5" x-data>
  <h2 class="text-2xl font-semibold text-gray-900">Product Master</h2>
  <div class="flex items-center justify-between mb-1 mt-5">
    <div class="w-full sm:max-w-xs flex justify-start relative">
      <span class="absolute inset-y-0 left-0 flex items-center pl-3">
        <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
      </span>
      <input type="text" placeholder="Search..."
        class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
    </div>
    <div class="flex items-center gap-2">
      <a wire:navigate href="{{ route('addproduct') }}">
        <x-button emerald right-icon="plus" />
      </a>
      <x-button right-icon="pencil" interaction="positive" x-bind:class="selected.length === 0 
              ? 'bg-gray-300 text-white cursor-not-allowed' 
              : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'" x-bind:disabled="selected.length === 0"
        x-on:click="$openModal('Edit')">
      </x-button>
      {{-- @include('partials.product-modal.product-edit') --}}
      <x-button right-icon="trash" interaction="negative" x-bind:class="selected.length === 0 
              ? 'bg-red-300 text-white cursor-not-allowed' 
              : 'bg-red-600 hover:bg-red-700 text-white'" x-bind:disabled="selected.length === 0"
        x-on:click="$openModal('Delete')">
      </x-button>
      {{-- @include('partials.product-modal.product-delete') --}}
    </div>
  </div>

  <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md">
    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-4">
            <input type="checkbox" wire:click="toggleAll" />
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
        @forelse ($products as $product)
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-4">
        <input type="checkbox" wire:model="selected" value="{{ $product->id }}" class="h-4 w-4 text-blue-600" />
        </td>
        <td class="px-6 py-4">{{ $product->barcode }}</td>
        <td class="px-6 py-4">{{ $product->supplier }}</td>
        <td class="px-6 py-4">{{ $product->description }}</td>
        <td class="px-6 py-4">{{ $product->highest_uom }}</td>
        <td class="px-6 py-4">{{ $product->lowest_uom }}</td>
        <td class="px-6 py-4">{{ number_format($product->price, 2) }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="7" class="text-center py-6 text-gray-500">No products found.</td>
      </tr>
    @endforelse
      </tbody>
    </table>
    <div class="my-4 px-4">
      <hr class="mb-2">
      {{ $products->links() }}
    </div>
  </div>
</div>