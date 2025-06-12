<div x-cloak class="grid grid-cols-1 lg:grid-cols-1 gap-4 w-full md:w-full mx-auto ">
    <!-- LEFT SIDE: Supplier Master Table (2/3 width) -->
    <div class="lg:col-span-2 space-y-1">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-900">Customer PO</h2>

        <!-- Tabs -->
        <div class="flex flex-wrap gap-2 mb-2 pt-2">
            <x-button rounded="lg" light teal icon="user" label="DR" @click="filterByStatus('DR')"
                :class="currentTab === 'DR' ? 'bg-blue-600' : 'bg-gray-300'" class="" />

            <x-button rounded="lg" light teal icon="user" label="Invoice" @click="filterByStatus('Invoice')"
                :class="currentTab === 'Invoice' ? 'bg-green-600' : 'bg-gray-300'" class="" />

            <x-button rounded="lg" light teal icon="user" label="Costumerpo" @click="filterByStatus('Costumerpo')"
                :class="currentTab === 'Costumerpo' ? 'bg-green-600' : 'bg-gray-300'" class="" />
        </div>
        <!-- end tabs -->

        <!-- Search and Buttons -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Search Bar -->
            <div class="w-full sm:max-w-xs flex justify-start relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
                </span>
                <input type="text" x-model="search" placeholder="Search..."
                    class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
            </div>

            <!-- Button Group -->
            <div class="flex gap-2">
                <x-button emerald right-icon="plus" x-on:click="$openModal('Add')" />


                <x-button right-icon="pencil" interaction="positive" x-bind:class="selected.length === 0 ?
                        'bg-gray-300 text-white cursor-not-allowed' :
                        'bg-[#12ffac] hover:bg-[#13eda1] text-white'" x-bind:disabled="selected.length === 0"
                    x-on:click="$openModal('Edit')">
                </x-button>


                <x-button right-icon="trash" interaction="negative" x-bind:class="selected.length === 0 ?
                        'bg-red-300 text-white cursor-not-allowed' :
                        'bg-red-600 hover:bg-red-700 text-white'" x-bind:disabled="selected.length === 0"
                    x-on:click="$openModal('Delete')">
                </x-button>

            </div>
        </div>

        <!-- Customer Table -->
        <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full">
            <table class="min-w-xl w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-4">
                            <input type="checkbox" @change="toggleAll" :checked="isAllSelected"
                                class="h-4 w-4 text-blue-600" />
                        </th>
                        <th class="px-6 py-4 font-medium text-gray-900">PO #</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Customer</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Date</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Status</th>
                        <th class="px-6 py-4 font-medium text-gray-900">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    <template x-for="poItem in po" :key="poItem . id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <input type="checkbox" :value="poItem . id" x-model="selected"
                                    class="h-4 w-4 text-blue-600" />
                            </td>
                            <td class="px-6 py-4" x-text="poItem.PO"></td>
                            <td class="px-6 py-4" x-text="poItem.Customer"></td>
                            <td class="px-6 py-4" x-text="poItem.Date"></td>
                            <td class="px-6 py-4" x-text="poItem.Status"></td>
                            <td class="px-6 py-4" x-text="poItem.Total"></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- RIGHT SIDE: Add PO Form (1/3 width) -->
    <div class="col-span-1 w-full md:w-full bg-white rounded-lg border shadow-md p-5 space-y-4 mt-5 mx-auto ml-1">
        <h3 class="text-lg font-bold text-gray-800">
            Add <span class="text-blue-500">PO</span> to <span class="text-blue-500">Customer</span>
        </h3>
    
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Customer</label>
                <select wire:model="selectedCustomerId" class="block w-full rounded-md border border-gray-300 py-2 px-3">
                    <option value="">Select a customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
    
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Type</label>
                <select wire:model="receiptType" class="block w-full rounded-md border border-gray-300 py-2 px-3">
                    <option value="">Select type</option>
                    <option value="DR">DR</option>
                    <option value="INVOICE">INVOICE</option>
                </select>
            </div>
    
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" wire:model="poDate"
                    class="block w-full rounded-md border border-gray-300 py-2 px-3" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Discount</label>
                <input type="number" min="0" step="0.01" wire:model.lazy="purchase_discount" class="border border-gray-300 rounded-md px-3 py-2 w-full" />
            </div>            
        </div>
    
        <h4 class="text-md font-semibold text-gray-700">Products</h4>
        <div class="overflow-x-auto">
            <table wire:poll class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1 font-medium">Barcode</th>
                        <th class="border px-2 py-1 font-medium">Product Description</th>
                        <th class="border px-2 py-1 font-medium">Qty</th>
                        <th class="border px-2 py-1 font-medium">Unit Price</th>
                        <th class="border px-2 py-1 font-medium">Product Discount</th>
                        <th class="border px-2 py-1 font-medium">Subtotal</th>
                        <th class="border px-2 py-1 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $index => $p)
                        <tr class="hover:bg-gray-50">
                            <td wire:poll.prevent class="border px-2 py-2">
                                <input type="text"
                                    wire:model.lazy="products.{{ $index }}.barcode"
                                    list="barcodes"
                                    placeholder="enter and select barcode"
                                    class="w-full border-gray-300 rounded-md px-2 py-1 text-sm"
                                    wire:change="fillProductByBarcode({{ $index }})"
                                />

                                <datalist id="barcodes">
                                    @foreach($allProducts as $product)
                                        <option value="{{ $product['barcode'] }}">{{ $product['description'] }}</option>
                                    @endforeach
                                </datalist>
                            </td>
                            {{-- //HOYY!! DRI KA NAG UNTAT --}}
                            <td wire:ignore.self class="border px-2 py-2">
                                <input type="text"
                                    wire:model.lazy="products.{{ $index }}.product_description"
                                    list="product_descriptions"
                                    placeholder="enter and select description"
                                    class="w-full border-gray-300 rounded-md px-2 py-1 text-sm"
                                    wire:change="fillProductByDescription({{ $index }})"
                                />
                                <datalist id="product_descriptions">
                                    @foreach($allProducts as $product)
                                        <option value="{{ $product['description'] }}">{{ $product['barcode'] }}</option>
                                    @endforeach
                                </datalist>
                            </td>                            
                            <td class="border px-2 py-2">
                                <input type="number"
                                    wire:model.lazy="products.{{ $index }}.quantity"
                                    wire:input="updateTotal({{ $index }})"
                                    min="1"
                                    class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" 
                                />
                            </td>
                            <td class="border px-2 py-2">
                                <input type="number"
                                    step="0.01"
                                    readonly
                                    value="{{ $products[$index]['price'] ?? 0 }}"
                                    class="w-full border-gray-300 rounded-md px-2 py-1 text-sm bg-gray-100" />
                            </td>
                            <td class="border px-2 py-2">
                                <input type="number" min="0" step="0.01"
                                       wire:model.lazy="products.{{ $index }}.product_discount"
                                       wire:input="updateTotal({{ $index }})"
                                       class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" />
                            </td>
                            <td class="border px-2 py-2">
                                <div wire:loading wire:target="updateTotal"
                                    class="w-[200px] flex items-center bg-gray-100 border border-gray-300 rounded-md px-2 py-1 text-sm">  
                                    <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-400 italic">calculating...</span>
                                </div>

                                <div wire:loading.remove wire:target="updateTotal">
                                    <input type="text" value="{{ number_format($p['total'] ?? 0, 2) }}" readonly
                                        class="w-full bg-gray-100 border-gray-300 rounded-md px-2 py-1 text-sm" />
                                </div>
                            </td>                            
                            <td class="border px-2 py-2 text-center">
                                <x-button red label="Remove" class="px-2 py-1 text-xs h-8"
                                    wire:click="removeProduct({{ $index }})" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="5" class="border px-2 py-2 text-right font-semibold">Total:</td>
                        <td class="border px-2 py-2 font-semibold text-right">
                            <div wire:loading wire:target="updateTotal, fillProductByBarcode, fillProductByDescription" class="flex justify-end items-center space-x-2">
                                <svg class="animate-spin h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                <span class="text-gray-500 text-sm italic">Calculating...</span>
                            </div>
                            <div wire:loading.remove wire:target="updateTotal, fillProductByBarcode, fillProductByDescription">
                                {{ number_format($grandTotal, 2) }}
                            </div>
                        </td>
                        <td class="border px-2 py-2"></td>
                    </tr>
                </tfoot>                
            </table>
    
            <div class="pt-2 ml-2">
                <x-button green label="Add Product" wire:click="addProduct" />
            </div>
    
            <div class="pt-4">
                <x-textarea wire:model="remarks" name="remarks" label="Remarks" placeholder="Write your remarks" />
                <div class="flex justify-end pt-2">
                    <x-button blue label="Submit" wire:click="submitPO" />
                </div>
            </div>
        </div>
    </div>
    
</div>

