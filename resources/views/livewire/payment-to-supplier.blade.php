<div>
  <h2 class="text-xl font-semibold">Payment To Supplier</h2>
  @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition class="mt-2">
    <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
      class="!bg-green-300 !w-full" />
    </div>
  @endif
  <div class="p-4 space-y-6 max-w-6xl mx-auto border rounded-lg border-gray-200">
    <form wire:submit.prevent="savePayments">
      <div class="mb-2">
        <label for="date" class="block mb-1 font-semibold">Select Date</label>
        <input type="date" id="date" wire:model="date" class="border rounded px-3 py-2 w-full" required />
      </div>
      <div class="mb-2">
        <label for="customer" class="block mb-1 font-semibold">Select Supplier</label>
        <select wire:model.live="filterSupplier" class="rounded border px-3 py-2 text-sm w-full " required>
          <option value="">Select Supplier</option>
          @foreach ($supplierOptions as $id => $name)
        <option value="{{ $id }}">{{ $name }}</option>
      @endforeach
        </select>
        @error('filterSupplier') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>
      <div class="overflow-auto rounded border border-gray-300 mt-3">
        <table class="w-full text-left text-sm text-gray-700">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">Received Number</th>
              <th class="px-4 py-2">Received Date</th>
              <th class="px-4 py-2">Received Type</th>
              <th class="px-4 py-2">Received Total Amount</th>
              <th class="px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          @if ($filterSupplier)
          <tbody>
          @foreach ($selectedReceived as $recid)
          <tr class="border-t">
          @php
        $formatted = str_pad($recid['id'], 9, '0', STR_PAD_LEFT);
        $displayId = 'RCV-' . substr($formatted, 0, 3) . '-' . substr($formatted, 3, 3) . '-' . substr($formatted, 6, 3);
        @endphp
          <td class="px-4 py-2">{{ $displayId }}</td>
          <td class="px-4 py-2">{{ \Carbon\Carbon::parse($recid['created_at'])->format('F d, Y') }}</td>
          <td class="px-4 py-2">{{ $recid['receipt_type'] }}</td>
          <td class="px-4 py-2">₱{{ number_format($recid['grand_total'], 2) }}</td>
          <td class="px-4 py-2 text-center flex justify-center gap-2">
          <button wire:click.prevent="addToTotal({{ $recid['id'] }})" @if(in_array($recid['id'], $addedReceivings)) disabled @endif
            class="!h-6 px-3 border rounded text-green-600 border-green-600 hover:bg-green-50">
            Add to Total
            <span wire:loading wire:target="addToTotal({{ $recid['id'] }})">Loading...</span>
          </button>
          <button wire:click.prevent="removeFromTotal({{ $recid['id'] }})" wire:loading.attr="disabled"
            wire:target="removeFromTotal({{ $recid['id'] }})"
            class="!h-6 px-3 border rounded text-red-600 border-red-600 hover:bg-red-50"
            wire:key="remove-btn-{{ $recid['id'] }}">
            <span wire:loading.remove wire:target="removeFromTotal({{ $recid['id'] }})">Remove</span>
            <span wire:loading wire:target="removeFromTotal({{ $recid['id'] }})">Removing...</span>
          </button>
          </td>
          </tr>
        @endforeach
          </tbody>
          <tfoot class="bg-gray-100 font-semibold">
          <tr>
            <td colspan="4" class="px-4 py-2 text-right">Total Amount:</td>
            <td colspan="4" class="px-4 py-2">₱{{ number_format($totalAmount, 2) }}</td>
          </tr>
          </tfoot>
      @else
        <tbody>
        <tr>
          <td colspan="5" class="px-6 py-4 text-center text-gray-500">
          No data found. Please select a supplier.
          </td>
        </tr>
        </tbody>
      @endif
        </table>
      </div>
      @if ($errors->has('selectedReceivedIds'))
      <p class="text-red-600 text-sm mt-1">{{ $errors->first('selectedReceivedIds') }}</p>
    @endif

      <div class="overflow-auto rounded border border-gray-300 mt-6">
        <h2 class="text-lg font-semibold mb-2">Returns from Supplier</h2>
        <table class="w-full text-left text-sm text-gray-700">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">Return Number</th>
              <th class="px-4 py-2">Return Date</th>
              <th class="px-4 py-2">Return Type</th>
              <th class="px-4 py-2">Return Amount</th>
              <th class="px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          @if ($filterSupplier)
          <tbody>
          @foreach ($selectedReturns as $return)
          <tr class="border-t">
          @php
        $formatted = str_pad($return['id'], 9, '0', STR_PAD_LEFT);
        $returnId = 'RTN-' . substr($formatted, 0, 3) . '-' . substr($formatted, 3, 3) . '-' . substr($formatted, 6, 3);
        @endphp
          <td class="px-4 py-2">{{ $returnId }}</td>
          <td class="px-4 py-2">{{ \Carbon\Carbon::parse($return['created_at'])->format('F d, Y') }}</td>
          <td class="px-4 py-2">{{ $return['return_type'] }}</td>
          <td class="px-4 py-2">₱{{ number_format($return['total'], 2) }}</td>
          <td class="px-4 py-2 text-center flex justify-center gap-2">
          <button wire:click.prevent="addReturnToTotal({{ $return['id'] }})" @if(in_array($return['id'], $addedReturns)) disabled @endif
            class="!h-6 px-3 border rounded text-green-600 border-green-600 hover:bg-green-50">
            Add to Total
            <span wire:loading wire:target="addReturnToTotal({{ $return['id'] }})">Loading...</span>
          </button>
          <button wire:click.prevent="removeReturnFromTotal({{ $return['id'] }})" wire:loading.attr="disabled"
            class="!h-6 px-3 border rounded text-red-600 border-red-600 hover:bg-red-50">
            <span wire:loading.remove wire:target="removeReturnFromTotal({{ $return['id'] }})">Remove</span>
            <span wire:loading wire:target="removeReturnFromTotal({{ $return['id'] }})">Removing...</span>
          </button>
          </td>
          </tr>
        @endforeach
          </tbody>
          <tfoot class="bg-gray-100 font-semibold">
          <tr>
            <td colspan="4" class="px-4 py-2 text-right">Total Returns Amount:</td>
            <td colspan="4" class="px-4 py-2">₱{{ number_format($totalReturnsAmount, 2) }}</td>
          </tr>
          </tfoot>
      @else
        <tbody>
        <tr>
          <td colspan="5" class="px-6 py-4 text-center text-gray-500">
          No returns found. Please select a supplier.
          </td>
        </tr>
        </tbody>
      @endif
        </table>
      </div>
      <div class="mt-6">
        <label class="block mb-1 font-semibold text-gray-700">Payable Amount</label>
        <div class="w-full px-3 py-2 border rounded bg-gray-100 text-gray-800 font-semibold">
          ₱{{ number_format($this->payableAmount, 2) }}
        </div>
      </div>

      <div class="space-y-4">
        <!-- Payment Method Select -->
        <div wire:poll>
          <label for="paymentMethod" class="block mb-1 font-semibold">Select Payment Method</label>
          <select id="paymentMethod" wire:model="paymentMethod" class="w-full border rounded px-3 py-2">
            <option value="" disabled>Select Method</option>
            <option value="Cash">Cash</option>
            <option value="Check">Check</option>
            <option value="Bank Transfer">Bank Transfer</option>
          </select>
          @error('paymentMethod')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror

        </div>

        <!-- Show Check Fields -->
        @if ($paymentMethod === 'Check')
        <div class="space-y-3 border rounded-lg p-4 bg-gray-50">
          <div>
          <label class="block mb-1 font-semibold">Select Bank</label>
          <select wire:model="checkBank" class="w-full border rounded px-3 py-2">
            <option value="" disabled>Select Bank</option>
            <!-- bank options -->
          </select>
          @error('checkBank')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
          </div>
          <div>
          <label class="block mb-1 font-semibold">Cheque Number</label>
          <input type="text" wire:model="chequeNumber" class="w-full border rounded px-3 py-2" />
          @error('chequeNumber')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
          </div>
          <div>
          <label class="block mb-1 font-semibold">Check Date</label>
          <input type="date" wire:model="checkDate" class="w-full border rounded px-3 py-2" />
          @error('checkDate')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
          </div>
        </div>
    @endif

        <!-- Show Bank Transfer Fields -->
        @if ($paymentMethod === 'Bank Transfer')
        <div class="space-y-3 border rounded-lg p-4 bg-gray-50">
          <div>
          <label class="block mb-1 font-semibold">Select Bank</label>
          <select wire:model="transferBank" class="w-full border rounded px-3 py-2">
            <option value="" disabled>Select Bank</option>
            <!-- bank options -->
          </select>
          @error('transferBank')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
          </div>
          <div>
          <label class="block mb-1 font-semibold">Reference Number</label>
          <input type="text" wire:model="referenceNumber" class="w-full border rounded px-3 py-2" />
          @error('referenceNumber')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
          </div>
          <div>
          <label class="block mb-1 font-semibold">Transaction Date</label>
          <input type="date" wire:model="transactionDate" class="w-full border rounded px-3 py-2" />
          @error('transactionDate')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
          </div>
        </div>
    @endif
      </div>


      <div>
        <label for="amount" class="block mb-1 font-semibold">Enter Amount</label>
        <input id="amount" type="number" wire:model.lazy="amount" class="w-full border rounded px-3 py-2" />
        @error('amount') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="deduction" class="block mb-1 font-semibold">Other Deduction</label>
        <input id="deduction" type="number" wire:model.lazy="deduction" class="w-full border rounded px-3 py-2"
          placeholder="₱0.00" />
      </div>
      <div>
        <label for="ewt_amount" class="block mb-1 font-semibold">EWT Amount</label>
        <input id="ewt_amount" type="number" wire:model.lazy="ewt_amount" class="w-full border rounded px-3 py-2"
          placeholder="₱0.00" />
      </div>
      <div>
        <label for="remarks" class="block mb-1 font-semibold">Remarks</label>
        <textarea id="remarks" wire:model.lazy="remarks" rows="3" class="w-full border rounded px-3 py-2"
          placeholder="Add notes..."></textarea>
      </div>

      <div class="flex justify-end mt-6">
        <button type="submit" wire:loading.attr="disabled" wire:target="savePayments"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
          <span wire:loading wire:target="savePayments" class="animate-spin mr-2">
            <svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
          </span>
          Save as Payment
        </button>

      </div>
    </form>
  </div>
</div>