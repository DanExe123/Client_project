<div x-data="{
    currentTab: 'For Approval',
    filterByStatus(status) {
        this.currentTab = status;
    }
}">
    <div>
                <div class="space-y-1">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-2">Recieving</h2>
        
                    <!-- Tab Buttons -->
                    <div class="flex flex-wrap gap-2 pt-2 mb-2">
                        <x-button 
                            rounded="lg" 
                            light 
                            teal  
                            icon="check-circle"  
                            label="For Approval"  
                            @click="filterByStatus('For Approval')" 
                            x-bind:class="currentTab=== 'For Approval' ? 'bg-blue-600' : 'bg-gray-300'"
                            
                        />
        
                        <x-button 
                            rounded="lg" 
                            light 
                            green 
                            icon="user" 
                            label="Approved" 
                            @click="filterByStatus('Approved')" 
                            x-bind:class="currentTab === 'Approved' ? 'bg-green-600' : 'bg-gray-300'"
                        />
        
                       
                    <x-button rounded="lg" light red icon="x-circle" label="Cancelled" @click="filterByStatus('Cancelled')" 
                    x-bind:class="currentTab === 'Cancelled' ? 'bg-green-600' : 'bg-gray-300'" 
                    class=""
                    />
                            



            </div>
           
            
            @php
            $message = session('message');
            $isCancelled = str_contains(strtolower($message), 'cancel');
        @endphp
        
        @if ($message)
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition class="mt-2">
                @if ($isCancelled)
                    <x-alert 
                        :title="$message"
                        :description="$message"
                        negative 
                        class="!bg-red-300 !w-full"
                    />
                @else
                    <x-alert 
                        :title="$message" 
                        icon="check-circle" 
                        color="success" 
                        class="!bg-green-300 !w-full" 
                        positive 
                        flat 
                    />
                @endif
            </div>
        @endif
        
        
        

            <!-- Action Buttons -->
            <div class="flex gap-2 justify-end">
                <div class="w-full sm:max-w-xs flex justify-start relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
                    </span>
                    <input
                    type="text"
                    wire:model.debounce.300ms="search"
                    placeholder="Search..."
                    class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />

                </div>

                <x-button 
                left-icon="check-circle" 
                interaction="primary" 
                wire:click="approveSelected"
                :class="count($selectedpoId ?? []) !== 1 
                    ? 'bg-gray-300 text-white cursor-not-allowed' 
                    : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'" 
                :disabled="count($selectedpoId ?? []) !== 1">
                Approve
            </x-button>
            
                
            <x-button 
            right-icon="pencil" 
            interaction="positive"
            wire:click="editSelected"
            :class="count($selectedpoId ?? []) !== 1 
                ? 'bg-gray-300 text-white cursor-not-allowed' 
                : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'" 
            :disabled="count($selectedpoId ?? []) !== 1">
            Edit
        </x-button>
        
            </div>


                    @php
            $tabs = [
                'For Approval' => $forApprovalOrders,
                'Approved' => $approvedOrders,
                'Cancelled' => $cancelledOrders,
            ];
        @endphp

        @foreach ($tabs as $tab => $orders)
            <div x-show="currentTab === '{{ $tab }}'" x-cloak>
                <div class="overflow-auto rounded-lg border border-gray-200 shadow-md">
                    <table class="min-w-[800px] w-full border-collapse bg-white text-left text-sm text-gray-500">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-4 font-medium text-gray-900">
                                    <input type="checkbox" wire:click="toggleSelectAll"
                                        @if ($orders->pluck('id')->diff($selectedpoId)->isEmpty()) checked @endif />
                                </th>
                                <th class="px-6 py-4 font-medium text-gray-900">PO #</th>
                                <th class="px-6 py-4 font-medium text-gray-900">Customer</th>
                                <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                                <th class="px-6 py-4 font-medium text-gray-900">Transaction</th>
                                <th class="px-6 py-4 font-medium text-gray-900">Status</th>
                                <th class="px-6 py-4 font-medium text-gray-900">Total</th>
                                <th class="px-6 py-4 font-medium text-gray-900">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                            @forelse ($orders as $po)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <input type="checkbox" wire:click="selectedPo({{ $po->id }})"
                                            @if (in_array($po->id, $selectedpoId)) checked @endif />
                                    </td>
                                    <td class="px-6 py-4">{{ $po->po_number }}</td>
                                    <td class="px-6 py-4">{{ $po->supplier->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $po->order_date->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4">{{ $po->receipt_type }}</td>
                                    <td class="px-6 py-4">{{ ucfirst($po->status) }}</td>
                                    <td class="px-6 py-4">₱{{ number_format($po->total_amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                        <a href="{{ route('view-detail-recieving', ['id' => $po->id]) }}">
                                            <x-button outline primary label="View" />
                                        </a>

                                        @if ($tab === 'For Approval')
                                        <a href="{{ route('recieving.cancel', ['id' => $po->id]) }}"
                                            onclick="return confirm('Are you sure you want to cancel this purchase order?')">
                                             <x-button 
                                                 outline 
                                                 negative 
                                                 label="Cancel"
                                             />
                                         </a>
                                         
                                    @endif
                                    
                                    </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-gray-400">
                                        No records found for {{ $tab }}.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

            

    </div>
</div>



