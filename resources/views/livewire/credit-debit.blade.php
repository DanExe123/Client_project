<div>
    <div  class="p-4 space-y-6">

        <!-- Nav Tabs -->
        <div class="flex space-x-2">

            <x-button 
            rounded="lg" 
            light 
            teal  
            icon="check-circle"  
            label="Credit"  
            @click="filterTab('Credit')" 
            x-bind:class="currentTab=== 'Credit' ? 'bg-blue-600' : 'bg-gray-300'"
            
        />
        <x-button 
            rounded="lg" 
            light 
            green 
            icon="user" 
            label="Debit" 
            @click="filterTab('Debit')" 
            x-bind:class="currentTab === 'Debit' ? 'bg-green-100' : 'bg-gray-300'"
        />
        </div>

        <!-- Search and Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">

            <div class="w-full sm:max-w-xs flex justify-start relative mb-4">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
                </span>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search..."
                    class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />
            </div>
            
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">

            <!-- Customer Filter -->
            <div>
                <label class="text-sm text-gray-700 font-medium mb-1 block">Select Customer</label>
                <select wire:model.live="filterCustomer"
                class="w-full rounded-md border-gray-300 px-3 py-2 shadow-sm text-sm">
            <option value="">All</option>
            @foreach ($customerOptions as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach                
        </select>        
            </div>
        
        <!-- Date Filter -->
        <div>
            <label class="text-sm text-gray-700 font-medium mb-1 block">Select Date</label>
            <input type="date"
                wire:model="filterDate"
                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                class="w-full rounded-md border-gray-300 px-3 py-2 shadow-sm text-sm" />
        </div>


        
            <!-- Invoice Filter -->
            <div>
                <label class="text-sm text-gray-700 font-medium mb-1 block">Invoice/DR</label>
                <select wire:model="filterInvoice"
                        class="w-full rounded-md border-gray-300 px-3 py-2 shadow-sm text-sm">
                    <option value="">All</option>
                    @foreach ($invoiceOptions as $invoice)
                        <option value="{{ $invoice }}">{{ $invoice }}</option>
                    @endforeach
                </select>
            </div>
        
       <!-- Return Slip Filter -->
            <div>
                <label class="text-sm text-gray-700 font-medium mb-1 block">Return Slip</label>
                <select wire:model="filterSlip"
                        class="w-full rounded-md border-gray-300 px-3 py-2 shadow-sm text-sm">
                    <option value="">All</option>
                    @foreach ($slipOptions as $slip)
                    <option value="{{ $slip }}">{{ $slip }}</option>
                @endforeach
                </select>
            </div>

        
        </div>
        
        <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mt-4">
            <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-4 font-medium text-gray-900">Barcode</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Product Description</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Quantity</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Unit Price</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Subtotal</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Total Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @if ($filterCustomer)
                        @forelse ($returnItems as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $item->product_barcode }}</td>
                                <td class="px-6 py-4">{{ $item->product_description }}</td>
                                <td class="px-6 py-4">{{ $item->quantity }}</td>
                                <td class="px-6 py-4">₱{{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4">₱{{ number_format($item->subtotal, 2) }}</td>
                                <td class="px-6 py-4">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No return items found for this customer.
                                </td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No data found. Please select a customer.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
    
    
    

    </div>
</div>