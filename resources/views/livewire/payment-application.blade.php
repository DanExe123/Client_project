<div>
    <h2 class="text-xl font-semibold">Payment Application</h2>
   {{-- Success Alert --}}
   @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
        class="mt-2">
        <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
            class="!bg-green-300 !w-full" />
    </div>
    @endif

  
    <div class="p-4 space-y-6 max-w-6xl mx-auto border rounded-lg border-gray-200">
      <form wire:submit.prevent="savePayment">
      <!-- Select Date -->
      <div class="mb-2">
          <label for="date" class="block mb-1 font-semibold">Select Date</label>
          <input type="date" id="date" wire:model="date" class="border rounded px-3 py-2 w-full"  required/>
      </div>
  
      <!-- Select Customer -->
      <div class="mb-2">
          <label for="customer" class="block mb-1 font-semibold">Select Customer</label>
          <select wire:model.live="filterCustomer"     class="rounded border px-3 py-2 text-sm w-full " required>
              <option value="">All Customers</option>
              @foreach ($customerOptions as $id => $name)
                  <option value="{{ $id }}">{{ $name }}</option>
              @endforeach
          </select>
          @error('filterCustomer') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
      </div>
  
    <!-- Invoice Table -->
        <div class="overflow-auto rounded border border-gray-300 mt-3">
            <table class="w-full text-left text-sm text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Invoice Number</th>
                        <th class="px-4 py-2">Invoice Date</th>
                        <th class="px-4 py-2">Invoice Amount</th>
                        <th class="px-4 py-2">Action</th>
                    </tr>
                </thead>
        
                @if ($filterCustomer)
                    <tbody>
                        @foreach ($selectedInvoices as $index => $inv)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $inv['number'] }}</td>
                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($inv['date'])->format('F d, Y') }}</td>
                <td class="px-4 py-2">₱{{ number_format($inv['amount'], 2) }}</td>
                <td class="text-center flex justify-center gap-2 my-2">
                    <button
                    wire:click.prevent="addToTotal({{ $inv['id'] }})"
                    wire:loading.attr="disabled"
                    wire:target="addToTotal({{ $inv['id'] }})"
                    class="!h-6 px-3 border rounded text-green-600 border-green-600 hover:bg-green-50"
                    wire:key="add-btn-{{ $inv['id'] }}"
                >
                    <span wire:loading.remove wire:target="addToTotal({{ $inv['id'] }})">Add to Total</span>
                    <span wire:loading wire:target="addToTotal({{ $inv['id'] }})">Loading...</span>
                </button>
                
                
                <button
                
                wire:click.prevent="removeFromTotal({{ $inv['id'] }})"
                wire:loading.attr="disabled"
                wire:target="removeFromTotal({{ $inv['id'] }})"
                class="!h-6 px-3 border rounded text-red-600 border-red-600 hover:bg-red-50"
                wire:key="remove-btn-{{ $inv['id'] }}"
            >
                <span wire:loading.remove wire:target="removeFromTotal({{ $inv['id'] }})">Remove</span>
                <span wire:loading wire:target="removeFromTotal({{ $inv['id'] }})">Removing...</span>
            </button>
            
                    
                </td>
            </tr>
        @endforeach
  
              </tbody>
  
              <tfoot class="bg-gray-100 font-semibold">
                  <tr>
                      <td colspan="3" class="px-4 py-2 text-right">Total Amount:</td>
                      <td colspan="2" class="px-4 py-2">₱{{ number_format($totalAmount, 2) }}</td>
                      </td>
                  </tr>
              </tfoot>
          @else
              <tbody>
                  <tr>
                      <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                          No data found. Please select a customer.
                      </td>
                  </tr>
              </tbody>
          @endif
      </table>
  </div>

    @if ($errors->has('selectedInvoiceIds'))
        <p class="text-red-600 text-sm mt-2 text-center">
            {{ $errors->first('selectedInvoiceIds') }}
        </p>
    @endif


  <!-- Amount Receivable -->
    <div wire:poll class="mt-4 p-4 bg-gray-50 border border-gray-300 rounded">
        <h3 class="text-md font-semibold ">Amount Receivable:</h3>
        <p class="text-lg font-bold">
            ₱{{ number_format($totalAmount, 2) }}
        </p>
    </div>
  
    <div class="space-y-4 mt-6">
        <!-- Payment Method -->
        <div>
            <label for="paymentMethod" class="block mb-1 font-semibold">Select Payment Method</label>
            <select id="paymentMethod"
                    wire:model="paymentMethod"
                    class="w-full border rounded px-3 py-2"
                    required
            >
                <option value="" disabled>Select Method</option>
                <option value="Cash">Cash</option>
                <option value="Check">Check</option>
                <option value="Bank Transfer">Bank Transfer</option>
            </select>
            @error('paymentMethod') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>
    
        <!-- Check Fields -->
        @if($paymentMethod === 'Check')
            <div class="space-y-3 border rounded-lg p-4 bg-gray-50">
                <div>
                    <label class="block mb-1 font-semibold">Select Bank</label>
                    <select wire:model="checkBank" class="w-full border rounded px-3 py-2" required>
                        <option value="" disabled>Select Bank</option>
                        <option>Bank A</option>
                        <option>Bank B</option>
                        <option>Bank C</option>
                    </select>
                    @error('checkBank') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Cheque Number</label>
                    <input type="text" wire:model="chequeNumber" class="w-full border rounded px-3 py-2" required />
                    @error('chequeNumber') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Check Date</label>
                    <input type="date" wire:model="checkDate" class="w-full border rounded px-3 py-2" required />
                    @error('checkDate') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif
    
        <!-- Bank Transfer Fields -->
        @if($paymentMethod === 'Bank Transfer')
            <div class="space-y-3 border rounded-lg p-4 bg-gray-50">
                <div>
                    <label class="block mb-1 font-semibold">Select Bank</label>
                    <select wire:model="transferBank" class="w-full border rounded px-3 py-2" required>
                        <option value="" disabled>Select Bank</option>
                        <option>Bank A</option>
                        <option>Bank B</option>
                        <option>Bank C</option>
                    </select>
                    @error('transferBank') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Reference Number</label>
                    <input type="text" wire:model="referenceNumber" class="w-full border rounded px-3 py-2" required />
                    @error('referenceNumber') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Transaction Date</label>
                    <input type="date" wire:model="transactionDate" class="w-full border rounded px-3 py-2" required />
                    @error('transactionDate') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>
            </div>
        @endif
    </div>
    
      
      <!-- Amount -->
    <!-- Amount -->
    <div>
        <label for="amount" class="block mb-1 font-semibold">Enter Amount</label>

        <input
            type="text"
            id="amount"
            wire:model.lazy="amount"
            inputmode="numeric"
            class="w-full border rounded px-3 py-2"
        />
        @error('amount') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    
  
      <!-- Other Deduction -->
      <div>
          <label for="deduction" class="block mb-1 font-semibold">Other Deduction</label>
          <input id="deduction" type="number" wire:model.lazy="deduction" class="w-full border rounded px-3 py-2" placeholder="₱0.00" />
      </div>
  
      <!-- EWT -->
      <div>
          <label for="ewt_amount" class="block mb-1 font-semibold">EWT Amount</label>
          <input id="ewt_amount" type="number" wire:model.lazy="ewt_amount" class="w-full border rounded px-3 py-2" placeholder="₱0.00" />
      </div>
  
      <!-- Remarks -->
      <div>
          <label for="remarks" class="block mb-1 font-semibold">Remarks</label>
          <textarea id="remarks" wire:model.lazy="remarks" rows="3" class="w-full border rounded px-3 py-2" placeholder="Add notes..."></textarea>
      </div>
  </div>
  
  
     <!-- Save Button -->
  <div class="flex justify-end mt-6">
    <x-button type="submit" blue label="Save as payment" />
  </div>
  
      </form>
  </div>
  
             
  </div>
  