<div>
  <div x-cloak class="space-y-2">
    <h2 class="text-2xl font-semibold text-gray-900">Return To Supplier</h2>
    {{-- Success Alert --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="mt-2">
            <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                class="!bg-green-300 !w-full" />
        </div>
    @endif
    <div class="flex gap-2 justify-end">
      <div class="w-full sm:max-w-xs flex justify-start relative">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
          <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
        </span>
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search..."
          class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
      </div>
      <x-button right-icon="pencil" interaction="positive" x-bind:class="selectedReturns.length === 0
                ? 'bg-gray-300 text-white cursor-not-allowed'
                : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'" x-bind:disabled="selectedReturns.length === 0"
        x-on:click="$openModal('Edit')">
      </x-button>

      <x-button right-icon="trash" interaction="negative" x-bind:class="selectedReturns.length === 0
                ? 'bg-red-300 text-white cursor-not-allowed'
                : 'bg-red-600 hover:bg-red-700 text-white'" x-bind:disabled="selectedReturns.length === 0"
        x-on:click="$openModal('Delete')">
      </x-button>
    </div>

    <div wire:poll class="overflow-auto rounded-lg border border-gray-200">
      <table class="min-w-[800px] w-full border-collapse bg-white text-left text-sm text-gray-500">
        <thead class="bg-gray-50 sticky top-0 z-10">
          <tr>
            <th class="px-4 py-4">
              <input type="checkbox" @change="toggleAllReturns" :checked="areAllReturnsSelected"
                class="h-4 w-4 text-blue-600" />
            </th>
            <th class="px-6 py-4 font-medium text-gray-900">Date</th>
            <th class="px-6 py-4 font-medium text-gray-900">Supplier</th>
            <th class="px-6 py-4 font-medium text-gray-900">Total</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
          @forelse ($returnOrders as $returnOrder)
            <tr>
              <td class="px-6 py-4">
                <input type="checkbox" 
                  class="h-4 w-4 text-blue-600" />
              </td>
              <td class="px-6 py-4">{{ \Carbon\Carbon::parse($returnOrder->order_date)->format('Y-m-d') }}</td>
              <td class="px-6 py-4">{{ $returnOrder->supplier->name ?? 'N/A' }}</td>
              <td class="px-6 py-4">₱{{ number_format($returnOrder->total_amount, 2) }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center py-6 text-gray-500">No return order found.</td>
                </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <hr>
    <!-- RIGHT SIDE: Add PO Form (1/3 width) -->
    <div wire:key="po-form-{{ $formKey }}" class="col-span-1 w-full md:w-full bg-white rounded-lg border shadow-md p-5 space-y-4 mt-5 mx-auto ml-1">
      <h3 class="text-lg font-bold text-gray-800">
          Add <span class="text-blue-500">Return</span> by <span class="text-blue-500">Customer</span>
      </h3>

      <div wire:loading wire:target="submitPO">
          <p class="text-blue-600 font-semibold">Submitting PO... please wait.</p>
      </div>

      <div wire:loading.remove wire:target="submitPO">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div> 
                  <label class="block text-sm font-medium text-gray-700 mb-1">Select Supplier</label>
                  <select wire:model="selectedSupplierId" class="block w-full rounded-md border border-gray-300 py-2 px-3">
                      <option value="">Select a supplier</option>
                      @foreach($suppliers as $supplier)
                          <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                      @endforeach
                  </select>
                  @error('selectedSupplierId')
                      <span class="text-sm text-red-500">{{ $message }}</span>
                  @enderror
              </div>
      
              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                  <input type="date" wire:model="poDate"
                      class="block w-full rounded-md border border-gray-300 py-2 px-3" />
              </div>        
          </div>
      
          <h4 class="text-md font-semibold text-gray-700">Products to Return</h4>
          <div class="overflow-x-auto">
              <table wire:poll class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                  <thead class="bg-gray-100">
                      <tr>
                          <th class="border px-2 py-1 font-medium">Barcode</th>
                          <th class="border px-2 py-1 font-medium">Product Description</th>
                          <th class="border px-2 py-1 font-medium">Qty</th>
                          <th class="border px-2 py-1 font-medium">Unit Price</th>
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
                                  @error('products')
                                      <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                                  @enderror
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
                                  @error('products')
                                      <div class="text-sm text-red-500 mt-1">{{ $message }}</div>
                                  @enderror
                              </td>                            
                              <td class="border px-2 py-2">
                                  <input type="number"
                                      wire:model.lazy="products.{{ $index }}.quantity"
                                      wire:input="updateTotal({{ $index }})"
                                      min="1"
                                      class="w-full border-gray-300 rounded-md px-2 py-1 text-sm" 
                                  />
                                  @error("products.{$index}.quantity")
                                      <span class="text-sm text-red-500">{{ $message }}</span>
                                  @enderror
                              </td>
                              <td class="border px-2 py-2">
                                  <input type="number"
                                      step="0.01"
                                      readonly
                                      value="{{ $products[$index]['price'] ?? 0 }}"
                                      class="w-full border-gray-300 rounded-md px-2 py-1 text-sm bg-gray-100" />
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
                          <td colspan="4" class="border px-2 py-2 text-right font-semibold">Total:</td>
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
                <button 
                    wire:click="addProduct"
                    @disabled(!$selectedSupplierId)
                    class="px-4 py-2 rounded-md text-white font-semibold
                        transition duration-150 ease-in-out
                        focus:outline-none focus:ring
                        {{ $selectedSupplierId ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 cursor-not-allowed' }}"
                    {{ !$selectedSupplierId ? 'disabled' : '' }}
                >
                    Add Product
                </button>
              </div>
      
              <div class="pt-4">
                  <x-textarea wire:model="remarks" name="remarks" label="Remarks" placeholder="Write your remarks" />
                  <div class="flex justify-end pt-2">
                      <x-button blue label="Submit" wire:click="submitReturn" />
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>

</div>