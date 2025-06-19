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
            <td colspan="3" class="px-4 py-2 text-right">Total Amount:</td>
            <td colspan="3" class="px-4 py-2">₱{{ number_format($totalAmount, 2) }}</td>
          </tr>
          </tfoot>
      @else
        <tbody>
        <tr>
          <td colspan="4" class="px-6 py-4 text-center text-gray-500">
          No data found. Please select a supplier.
          </td>
        </tr>
        </tbody>
      @endif
        </table>
      </div>
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
            <td colspan="3" class="px-4 py-2 text-right">Total Returns Amount:</td>
            <td colspan="3" class="px-4 py-2">₱{{ number_format($totalReturnsAmount, 2) }}</td>
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
      <div x-data="{ method: '' }" class="space-y-4">
        <div>
          <label for="PaymentMethod" class="block mb-1 font-semibold">Select Payment Method</label>
          <select id="PaymentMethod" x-model="method" @change="$dispatch('input', $event.target.value)"
            wire:model="PaymentMethod" class="w-full border rounded px-3 py-2">
            <option value="" disabled>Select Method</option>
            <option value="Cash">Cash</option>
            <option value="Check">Check</option>
            <option value="Bank Transfer">Bank Transfer</option>
          </select>
        </div>
        <div x-show="method === 'Check'" x-transition>
          <div class="space-y-3 border rounded-lg p-4 bg-gray-50">
            <div>
              <label class="block mb-1 font-semibold">Select Bank</label>
              <select wire:model="checkBank" class="w-full border rounded px-3 py-2">
                <option value="" disabled>Select Bank</option>
                <option>BDO Unibank</option>
                <option>Bank of the Philippine Islands (BPI)</option>
                <option>Metrobank</option>
                <option>Philippine National Bank (PNB)</option>
                <option>Land Bank of the Philippines (LANDBANK)</option>
                <option>China Banking Corporation (China Bank)</option>
                <option>Rizal Commercial Banking Corporation (RCBC)</option>
                <option>EastWest Bank</option>
                <option>Security Bank Corporation</option>
              </select>
            </div>
            <div>
              <label class="block mb-1 font-semibold">Cheque Number</label>
              <input type="text" wire:model="chequeNumber" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block mb-1 font-semibold">Check Date</label>
              <input type="date" wire:model="checkDate" class="w-full border rounded px-3 py-2" />
            </div>
          </div>
        </div>
        <div x-show="method === 'Bank Transfer'" x-transition>
          <div class="space-y-3 border rounded-lg p-4 bg-gray-50">
            <div>
              <label class="block mb-1 font-semibold">Select Bank</label>
              <select wire:model="transferBank" class="w-full border rounded px-3 py-2">
                <option value="" disabled>Select Bank</option>
                <option>Gcash</option>
                <option>BDO Unibank</option>
                <option>Bank of the Philippine Islands (BPI)</option>
                <option>Metrobank</option>
                <option>Philippine National Bank (PNB)</option>
                <option>Land Bank of the Philippines (LANDBANK)</option>
                <option>China Banking Corporation (China Bank)</option>
                <option>Rizal Commercial Banking Corporation (RCBC)</option>
                <option>EastWest Bank</option>
                <option>Security Bank Corporation</option>
              </select>
            </div>
            <div>
              <label class="block mb-1 font-semibold">Reference Number</label>
              <input type="text" wire:model="referenceNumber" class="w-full border rounded px-3 py-2" />
            </div>
            <div>
              <label class="block mb-1 font-semibold">Transaction Date</label>
              <input type="date" wire:model="transactionDate" class="w-full border rounded px-3 py-2" />
            </div>
          </div>
        </div>
        <div>
          <label for="amount" class="block mb-1 font-semibold">Enter Amount</label>
          <input id="amount" type="number" wire:model.lazy="amount" class="w-full border rounded px-3 py-2" />
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
      </div>
      <div class="flex justify-end mt-6">
        <x-button type="submit" wire:loading.attr="disabled" wire:target="savePayments" blue label="Save as Payment">
          <span wire:loading wire:target="savePayments" class="animate-spin mr-2 text-white">
            <svg class="w-4 h-4 inline-block" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
          </span>
        </x-button>
      </div>
    </form>
  </div>
</div>
<script>
  function toggleFields(method) {
    const checkFields = document.getElementById('checkFields');
    const bankTransferFields = document.getElementById('bankTransferFields');
    if (method === 'Check') {
      checkFields.style.display = 'block';
      bankTransferFields.style.display = 'none';
    } else if (method === 'Bank Transfer') {
      checkFields.style.display = 'none';
      bankTransferFields.style.display = 'block';
    } else {
      checkFields.style.display = 'none';
      bankTransferFields.style.display = 'none';
    }
  }
  document.addEventListener("livewire:load", function () {
    const paymentMethodSelect = document.getElementById('PaymentMethod');
    if (!paymentMethodSelect) return;
    // Run on initial load
    toggleFields(paymentMethodSelect.value);
    // Listen for manual change (user-initiated)
    paymentMethodSelect.addEventListener('change', function () {
      toggleFields(this.value);
    });
    // Listen for Livewire DOM updates
    Livewire.hook('message.processed', (message, component) => {
      const updatedSelect = document.getElementById('PaymentMethod');
      if (updatedSelect) toggleFields(updatedSelect.value);
    });
  });
</script>