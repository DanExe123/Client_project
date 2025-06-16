<div>
  <h2 class="text-xl font-semibold">Payment to Supplier</h2>

  @if (session()->has('message'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition class="mt-2">
    <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
      class="!bg-green-300 !w-full" />
    </div>
  @endif

  <div class="p-4 space-y-6 max-w-6xl mx-auto border rounded-lg border-gray-200">
    <form wire:submit.prevent="savePayment">
      <!-- Select Date -->
      <div class="mb-2">
        <label for="date" class="block mb-1 font-semibold">Select Date</label>
        <input type="date" id="date" wire:model="date" class="border rounded px-3 py-2 w-full" required />
      </div>

      <!-- Select Supplier -->
      <div class="mb-2">
        <label for="supplier" class="block mb-1 font-semibold">Select Supplier</label>
        <select wire:model="filterSupplier" class="rounded border px-3 py-2 text-sm w-full" required>
          <option value="">All Suppliers</option>
          @foreach ($supplierOptions as $id => $name)
        <option value="{{ $id }}">{{ $name }}</option>
      @endforeach
        </select>
      </div>

      <!-- Receiving Table -->
      <div class="overflow-auto rounded border border-gray-300 mt-3">
        <table class="w-full text-left text-sm text-gray-700">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-4 py-2">Receiving No.</th>
              <th class="px-4 py-2">Receiving Date</th>
              <th class="px-4 py-2">Total Amount</th>
              <th class="px-4 py-2">Remove</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($selectedReceivings as $index => $rec)
        <tr class="border-t">
          <td class="px-4 py-2">{{ $rec['number'] }}</td>
          <td class="px-4 py-2">{{ \Carbon\Carbon::parse($rec['date'])->format('F d, Y') }}</td>
          <td class="px-4 py-2">₱{{ number_format($rec['amount'], 2) }}</td>
          <td class="px-4 py-2 text-center">
          <button wire:click="removeReceiving({{ $index }})" class="text-red-600 hover:underline">Remove</button>
          </td>
        </tr>
      @endforeach
          </tbody>
          <tfoot class="bg-gray-100 font-semibold">
            <tr>
              <td colspan="2" class="px-4 py-2 text-right">Total Amount:</td>
              <td colspan="2" class="px-4 py-2">₱{{ number_format($this->totalAmount, 2) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Payment Method -->
      <div class="mb-2">
        <label for="paymentMethod" class="block mb-1 font-semibold">Select Payment Method</label>
        <select id="paymentMethod" wire:model="paymentMethod" class="border rounded px-3 py-2 w-full">
          <option value="" disabled>Select Method</option>
          <option value="Cash">Cash</option>
          <option value="Check">Check</option>
          <option value="Bank Transfer">Bank Transfer</option>
        </select>
      </div>

      <!-- Enter Amount -->
      <div class="mb-2">
        <label for="amount" class="block mb-1 font-semibold">Enter Amount</label>
        <input id="amount" type="number" min="0" step="0.01" wire:model.lazy="amount"
          class="border rounded px-3 py-2 w-full" required />
      </div>

      <!-- Other Deduction -->
      <div class="mb-2">
        <label for="deduction" class="block mb-1 font-semibold">Other Deduction (optional)</label>
        <input id="deduction" type="number" min="0" step="0.01" wire:model.lazy="deduction" placeholder="₱0.00"
          class="border rounded px-3 py-2 w-full" />
      </div>

      <!-- EWT Amount -->
      <div class="mb-2">
        <label for="ewt" class="block mb-1 font-semibold">EWT Amount</label>
        <input id="ewt" type="number" min="0" step="0.01" wire:model.lazy="ewt" placeholder="₱0.00"
          class="border rounded px-3 py-2 w-full" required />
      </div>

      <!-- Remarks -->
      <div class="mb-2">
        <label for="remarks" class="block mb-1 font-semibold">Remarks</label>
        <textarea id="remarks" wire:model="remarks" rows="3" class="border rounded px-3 py-2 w-full"
          placeholder="Add any notes here..."></textarea>
      </div>

      <!-- Check or Bank Transfer Fields -->
      @if ($paymentMethod === 'Check' || $paymentMethod === 'Bank Transfer')
      <div class="space-y-4 mt-4 border-t pt-4">
      <div>
        <label for="bank" class="block mb-1 font-semibold">Select Bank</label>
        <select id="bank" wire:model="{{ $paymentMethod === 'Check' ? 'checkBank' : 'transferBank' }}"
        class="border rounded px-3 py-2 w-full">
        <option value="" disabled>Select Bank</option>
        <option>BDO Unibank</option>
        <option>Bank of the Philippine Islands (BPI)</option>
        <option>Metrobank</option>
        </select>
      </div>

      <div>
        <label for="refOrCheque" class="block mb-1 font-semibold">
        {{ $paymentMethod === 'Check' ? 'Cheque Number' : 'Reference Number' }}
        </label>
        <input id="refOrCheque" type="text"
        wire:model="{{ $paymentMethod === 'Check' ? 'chequeNumber' : 'referenceNumber' }}"
        class="border rounded px-3 py-2 w-full" />
      </div>

      <div>
        <label for="dateField" class="block mb-1 font-semibold">
        {{ $paymentMethod === 'Check' ? 'Check Date' : 'Transaction Date' }}
        </label>
        <input id="dateField" type="date"
        wire:model="{{ $paymentMethod === 'Check' ? 'checkDate' : 'transactionDate' }}"
        class="border rounded px-3 py-2 w-full" />
      </div>
      </div>
    @endif

      <!-- Save Button -->
      <div class="flex justify-end mt-6">
        <x-button type="submit" blue label="Save as payment" />
      </div>
    </form>
  </div>
</div>