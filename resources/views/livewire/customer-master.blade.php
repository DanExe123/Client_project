<div x-cloak class="m-5">
  <!-- Header with title and button group --> 
  <h2 class="text-2xl font-semibold text-gray-900">Customer Master</h2>
  <div class="flex items-center justify-end">
    <!-- Button Group -->
    <div class="flex items-center gap-2 mt-5">
      <x-select class="mb-1"
          placeholder="Select year"
          :options="[
              ['name' => '2025', 'id' => '2025'],
              ['name' => '2024', 'id' => '2024'],
              ['name' => '2023', 'id' => '2023'],
          ]" 
          option-label="name" 
          option-value="id"
      />

      <!-- Add Button -->
      <a wire:navigate href="{{ route('addcustomer') }}">
          <x-button emerald right-icon="plus" />
      </a>

      <x-button right-icon="pencil" wire:click="editSelected" :disabled="count($selectedCustomerId) !== 1"/>
       
      <x-button right-icon="trash" wire:click="deleteSelected" :disabled="count($selectedCustomerId) === 0"/>
            
    </div>
  </div>

  <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md">
    <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-4">
                  <input type="checkbox"
                      wire:click="toggleSelectAll"
                      @if($customers->pluck('id')->diff($selectedCustomerId)->isEmpty()) checked @endif
                  />
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
                    <input type="checkbox"
                        wire:click="selectCustomer({{ $customer->id }})"
                        @if(in_array($customer->id, $selectedCustomerId)) checked @endif
                    />
                  </td>
                  <td class="flex gap-3 px-6 py-4 font-normal text-gray-900">
                      <div class="text-sm">
                          <div class="font-medium text-gray-700">{{ $customer->name }}</div>
                          <div class="text-gray-400">{{ $customer->email }}</div>
                      </div>
                  </td>
                  <td class="px-6 py-4">
                      <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-1 text-xs font-semibold text-green-600">
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

      @if (session()->has('message'))
          <div 
              x-data="{ show: true }" 
              x-init="setTimeout(() => show = false, 3000)" 
              x-show="show" 
              x-transition 
              class="fixed top-8 right-4 z-50"
          >
              <x-alert 
                  :title="session('message')" 
                  icon="check-circle" 
                  color="success" 
                  positive 
                  flat 
                  class="!bg-green-300 !w-72 shadow-lg"
              />
          </div>
      @endif

</div>