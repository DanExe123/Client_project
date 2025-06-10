<div x-cloak class="m-5">
  <h2 class="text-2xl font-semibold text-gray-900">Product Master</h2>
  {{-- Success Alert --}}
  @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
        class="mt-2">
        <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
            class="!bg-green-300 !w-full" />
    </div>
  @endif
  
  <div class="flex items-center justify-between mb-1 mt-5">
    <!-- 🔍 Search Bar -->
    <div class="w-full sm:max-w-xs flex justify-start relative">
      <span class="absolute inset-y-0 left-0 flex items-center pl-3">
        <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
      </span>
      <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..."
        class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
    </div>

    <div class="flex items-center gap-2">
      <a wire:navigate href="{{ route('addproduct') }}">
        <x-button emerald right-icon="plus" />
      </a>

      <x-button 
          right-icon="pencil" 
          wire:click="editSelected" 
          :class="count($selectedProductId) !== 1 
              ? 'bg-gray-300 text-white cursor-not-allowed' 
              : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'" 
          :disabled="count($selectedProductId) !== 1" 
      />

      <x-button 
          right-icon="trash" 
          wire:click="deleteSelected" 
          :class="count($selectedProductId) === 0 
              ? 'bg-red-300 text-white cursor-not-allowed' 
              : 'bg-red-600 hover:bg-red-700 text-white'" 
          :disabled="count($selectedProductId) === 0" 
      />

      {{-- @include('partials.product-modal.product-delete') --}}
    </div>
  </div>

  <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md">
    <table wire:poll.1s class="w-full border-collapse bg-white text-left text-sm text-gray-500">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-4">
            <input type="checkbox"
              wire:click="toggleSelectAll"
              @if($products->pluck('id')->diff($selectedProductId)->isEmpty()) checked @endif
            />
          </th>
          <th class="px-6 py-4 font-medium text-gray-900">Barcode</th>
          <th class="px-6 py-4 font-medium text-gray-900">Supplier</th>
          <th class="px-6 py-4 font-medium text-gray-900">Product Description</th>
          <th class="px-6 py-4 font-medium text-gray-900">Highest Unit of Measurement</th>
          <th class="px-6 py-4 font-medium text-gray-900">Lowest Unit of Measurement</th>
          <th class="px-6 py-4 font-medium text-gray-900">Price</th>
          <th class="px-6 py-4 font-medium text-gray-900">Selling Price</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 border-t border-gray-100">
        @forelse ($products as $product)
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-4">
          <input type="checkbox"
              wire:click="selectProduct({{ $product->id }})"
              @if(in_array($product->id, $selectedProductId)) checked @endif
          />
        </td>
        <td class="px-6 py-4">{{ $product->barcode }}</td>
        <td class="px-6 py-4">{{ $product->supplier }}</td>
        <td class="px-6 py-4">{{ $product->description }}</td>
        <td class="px-6 py-4">{{ $product->highest_uom }}</td>
        <td class="px-6 py-4">{{ $product->lowest_uom }}</td>
        <td class="px-6 py-4">{{ number_format($product->price, 2) }}</td>
        <td class="px-6 py-4">{{ number_format($product->selling_price, 2) }}</td>
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