<div x-cloak class="m-5">
  <!-- Header with title and button group -->
  <h2 class="text-2xl font-semibold text-gray-900">Supplier Master</h2>
  {{-- Success Alert --}}
  @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition class="mt-2">
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
      <input type="text" wire:model.live.debounce.100ms="search" placeholder="Search..."
        class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
    </div>

    <!--  Button Group -->
    <div class="flex items-center gap-2">
      <!-- Add Button -->
      <a wire:navigate href="{{ route('addsupplier') }}">
        <x-button emerald right-icon="plus" />
      </a>

      <x-button right-icon="pencil" wire:click="editSelected" :class="count($selectedSupplierId) !== 1
    ? 'bg-gray-300 text-white cursor-not-allowed'
    : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'"
        :disabled="count($selectedSupplierId) !== 1" />

    </div>
  </div>


  <!-- Supplier Table -->
  <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md">
    <table wire:poll.1s class="w-full border-collapse bg-white text-left text-sm text-gray-500">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-4">
          </th>
          <th class="px-6 py-4 font-medium text-gray-900">Supplier Name</th>
          <th class="px-6 py-4 font-medium text-gray-900">Email</th>
          <th class="px-6 py-4 font-medium text-gray-900">Address</th>
          <th class="px-6 py-4 font-medium text-gray-900">Term</th>
          <th class="px-6 py-4 font-medium text-gray-900">Tin Number</th>
          <th class="px-6 py-4 font-medium text-gray-900">Contact</th>
          <th class="px-6 py-4 font-medium text-gray-900">Contact Person</th>
          <th class="px-6 py-4 font-medium text-gray-900">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 border-t border-gray-100">
        @forelse ($suppliers as $supplier)
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-4">
        <input type="checkbox" wire:click="selectSupplier({{ $supplier->id }})" @if(in_array($supplier->id, $selectedSupplierId)) checked @endif />
        </td>
        <td class="px-6 py-4">{{ $supplier->name }}</td>
        <td class="px-6 py-4">{{ $supplier->email }}</td>
        <td class="px-6 py-4">{{ $supplier->address }}</td>
        <td class="px-6 py-4">{{ $supplier->term }}</td>
        <td class="px-6 py-4">{{ $supplier->tin_number }}</td>
        <td class="px-6 py-4">{{ $supplier->contact }}</td>
        <td class="px-6 py-4">{{ $supplier->contact_person }}</td>
        <td class="px-6 py-4">
          <span
            class="inline-flex items-center gap-1 rounded-full px-2 py-1 text-xs font-semibold
              {{ $supplier->status ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
              <span class="h-1.5 w-1.5 rounded-full {{ $supplier->status ? 'bg-green-600' : 'bg-red-600' }}"></span>
               {{ $supplier->status ? 'Active' : 'Inactive' }}
            </span>
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="9" class="text-center py-6 text-gray-500">No suppliers found.</td>
      </tr>
    @endforelse
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="my-4 px-4">
      <hr class="mb-2">
      {{ $suppliers->links() }}
    </div>
  </div>
</div>