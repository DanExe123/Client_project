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
        <div x-data="{ showToast: false }" class="relative">
            <!-- Debit Button -->
            <x-button 
                rounded="lg" 
                light 
                green 
                icon="user" 
                label="Debit" 
                @click="showToast = true"
                x-bind:class="showToast ? 'bg-green-100' : 'bg-gray-300'"
            />
        
            <!-- Toast -->
            <div 
                x-show="showToast"
                x-transition
                x-init="$watch('showToast', value => { if (value) setTimeout(() => showToast = false, 4000) })"
                class="absolute top-0 right-0 left-15 mt-2 mr-2 z-50 bg-white border border-red-500 shadow-lg rounded-lg p-4 w-80"
            >
                <div class="font-semibold text-red-500 mb-1">
                   <span class="text-red-500"> Debit Page Unavailable</span>
                </div>
                <div class="text-sm text-gray-700">
                    This Debit page is not available for now. <br>
                    Please contact the developer or go to <strong>Developer Settings</strong> and send your concern.
                </div>
            </div>
        </div>
        
        
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

        @if (session()->has('message'))
        @php
            $message = session('message');
            $isWarning = $message === 'No data to save.';
        @endphp

        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition class="mt-2">
            <x-alert 
                :title="$message" 
                :icon="$isWarning ? 'x-circle' : 'check-circle'" 
                :color="$isWarning ? 'warning' : 'success'" 
                flat 
                :class="$isWarning ? '!bg-yellow-300' : '!bg-green-300'"
                class="!w-full" 
            />
        </div>
    @endif

    

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
                        <th class="px-6 py-4 font-medium text-gray-900">Return Date</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Barcode</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Product Description</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Quantity</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Unit Price</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Subtotal</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Total Amount</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Add to Save</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @if ($filterCustomer)
                        @forelse ($returnItems as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($item->return->order_date)->format('Y-m-d') }}</td>
                              <td class="px-6 py-4">{{ $item->product_barcode }}</td>
                                <td class="px-6 py-4">{{ $item->product_description }}</td>
                                <td class="px-6 py-4">{{ $item->quantity }}</td>
                                <td class="px-6 py-4">₱{{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4">₱{{ number_format($item->subtotal, 2) }}</td>
                                <td class="px-6 py-4">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                <td class="text-center flex justify-center gap-2 my-2">
                                    <button
                                    wire:click.prevent="addToSave({{ $item['id'] }})"
                                    wire:loading.attr="disabled"
                                    wire:target="addToSave({{ $item['id'] }})"
                                    class="!h-6 px-3 border rounded text-green-600 border-green-600 hover:bg-green-50"
                                    wire:key="add-btn-{{ $item['id'] }}"
                                >
                                    <span wire:loading.remove wire:target="addToSave({{ $item['id'] }})">Add to Save</span>
                                    <span wire:loading wire:target="addToSave({{ $item['id'] }})">Loading...</span>
                                </button>
                                
                                
                                <button
                                
                                wire:click.prevent="removeToSave({{ $item['id'] }})"
                                wire:loading.attr="disabled"
                                wire:target="removeToSave({{ $item['id'] }})"
                                class="!h-6 px-3 border rounded text-red-600 border-red-600 hover:bg-red-50"
                                wire:key="remove-btn-{{ $item['id'] }}"
                            >
                                <span wire:loading.remove wire:target="removeToSave({{ $item['id'] }})">Remove</span>
                                <span wire:loading wire:target="removeToSave({{ $item['id'] }})">Removing...</span>
                            </button>
                             
                                    
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    No return items found for this customer.
                                </td>
                            </tr>
                        @endforelse
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No data found. Please select a customer.
                            </td>
                        </tr>
                    @endif
                    <tfoot class="bg-gray-50">

                        @if ($savedRows->count())
                    
                            <!-- Section Header -->
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center bg-gray-100 text-gray-900 font-bold">
                                    Saved Credits
                                </td>
                            </tr>
                    
                            <!-- Table Headings -->
                            <tr class="bg-gray-50 text-xs font-semibold text-gray-700">
                                <td class="px-4 py-2">Return Date</td>
                                <td class="px-4 py-2">Barcode</td>
                                <td class="px-4 py-2">Description</td>
                                <td class="px-4 py-2">Quantity</td>
                                <td class="px-4 py-2">Unit Price</td>
                                <td class="px-4 py-2">Subtotal</td>
                                <td class="px-4 py-2">Total</td>
                            </tr>
                    
                            <!-- Table Rows -->
                            @foreach ($savedRows as $item)
                                <tr class="text-sm text-gray-600 bg-white">
                                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->return->order_date)->format('Y-m-d') }}</td>
                                    <td class="px-4 py-2">{{ $item->product_barcode }}</td>
                                    <td class="px-4 py-2">{{ $item->product_description }}</td>
                                    <td class="px-4 py-2">{{ $item->quantity }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($item->subtotal, 2) }}</td>
                                    <td class="px-4 py-2">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                </tr>
                            @endforeach
                    
                            <!-- Totals -->
                            <tr class="bg-gray-100 text-sm">
                                <td colspan="6" class="px-4 py-2 text-right font-semibold">Selected Total:</td>
                                <td colspan="2" class="px-4 py-2 font-bold text-green-600">
                                    ₱{{ number_format($totalAmount, 2) }}
                                </td>
                            </tr>
                            <tr class="bg-gray-100 text-sm">
                                <td colspan="6" class="px-4 py-2 text-right font-semibold">Released Items Total:</td>
                                <td colspan="2" class="px-4 py-2 font-bold text-blue-600">
                                    ₱{{ number_format($releasedTotalAmount, 2) }}
                                </td>
                            </tr>
                            <tr class="bg-gray-100 text-sm">
                                <td colspan="6" class="px-4 py-2 text-right font-semibold">Remaining Balance (Released - Selected):</td>
                                <td colspan="2" class="px-4 py-2 font-bold text-red-600">
                                    ₱{{ number_format($this->differenceAmount, 2) }}
                                </td>
                            </tr>
                            
                    
                        @else
                            <!-- Empty State -->
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-400">
                                    No items added yet.
                                </td>
                            </tr>
                        @endif
                    
                        <!-- Save Button -->
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-right border-t">
                                <button 
                                wire:click="saveCredit"
                                wire:loading.attr="disabled"
                                wire:target="saveCredit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition"
                            >
                                <span wire:loading.remove wire:target="saveCredit">
                                    Save
                                </span>
                                <span wire:loading wire:target="saveCredit">
                                    Saving...
                                </span>
                            </button>
                            
                            </td>
                        </tr>
                    
                    </tfoot>
                    
                     
                </table>   
                <div class="space-y-2 mb-2 px-2">
                    {{ $returnItemsPaginated->links() }}
                </div> 
            </div>
        
    
    
    

    </div>
</div>