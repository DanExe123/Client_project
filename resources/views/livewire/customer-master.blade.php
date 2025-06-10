<div x-cloak class="m-5" x-data="customerTable">
  <!-- Header with title and button group -->
  <h2 class="text-2xl font-semibold text-gray-900">Customer Master</h2>
  <div class="flex items-center justify-end">
    <!-- Button Group -->
    <div class="flex items-center gap-2 mt-5">
      <x-select class="mb-1" placeholder="Select year" :options="[
    ['name' => '2025', 'id' => '2025'],
    ['name' => '2024', 'id' => '2024'],
    ['name' => '2023', 'id' => '2023'],
  ]" option-label="name" option-value="id" />


      <!-- Add Button -->

      <a wire:navigate href="{{ route('addcustomer') }}">
        <x-button emerald right-icon="plus" />
      </a>

      <!-- Edit Button -->
      <x-button right-icon="pencil" interaction="positive" x-bind:class="selected.length === 0 
          ? 'bg-gray-300 text-white cursor-not-allowed' 
          : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'" x-bind:disabled="selected.length === 0"
        x-on:click="$openModal('Edit')">
      </x-button>
      {{-- @include('partials.customer-edit') --}}

      <!-- Delete Button -->
      <x-button right-icon="trash" interaction="negative" x-bind:class="selected.length === 0 
          ? 'bg-red-300 text-white cursor-not-allowed' 
          : 'bg-red-600 hover:bg-red-700 text-white'" x-bind:disabled="selected.length === 0"
        x-on:click="$openModal('Delete')">
      </x-button>
      @include('partials.customer-delete')

    </div>
  </div>

  <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md">
    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-4 py-4">
            <input type="checkbox" wire:click="toggleSelectAll"
              @if($customers->pluck('id')->diff($selectedCustomerId)->isEmpty()) checked @endif />
          </th>
          <th class="px-6 py-4 font-medium text-gray-900">Name</th>
          <th class="px-6 py-4 font-medium text-gray-900">Status</th>
          <th class="px-6 py-4 font-medium text-gray-900">Address</th>
          <th class="px-6 py-4 font-medium text-gray-900">Contact#</th>
          <th class="px-6 py-4 font-medium text-gray-900">Contact Person</th>
          <th class="px-6 py-4 font-medium text-gray-900">Term</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100 border-t border-gray-100">
        @forelse ($customers as $customer)
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-4">
        <input type="checkbox" wire:click="selectCustomer({{ $customer->id }})" @if(in_array($customer->id, $selectedCustomerId)) checked @endif />
        </td>
        <td class="flex gap-3 px-6 py-4 font-normal text-gray-900">
        <div class="text-sm">
          <div class="font-medium text-gray-700">{{ $customer->name }}</div>
          <div class="text-gray-400">{{ $customer->email }}</div>
        </div>
        </td>
        <td class="px-6 py-4">
        <span
          class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">
          <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
          {{ $customer->status ? 'Active' : 'Inactive' }}
        </span>
        </td>
        <td class="px-6 py-4">{{ $customer->address }}</td>
        <td class="px-6 py-4">{{ $customer->contact }}</td>
        <td class="px-6 py-4">{{ $customer->contact_person }}</td>
        <td class="px-6 py-4">{{ $customer->term }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="7" class="text-center py-6 text-gray-500">No customers found.</td>
      </tr>
    @endforelse
      </tbody>
    </table>

    <div class="my-4 px-4">
      <hr class="mb-2">
      {{ $customers->links() }}
    </div>
  </div>