<div>
  <h2 class="text-xl font-semibold">Payment Application</h2>
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
    </div>

    <!-- Invoice Filter -->
    <div class="mb-2">
        <label class="text-sm text-gray-700 font-medium mb-1 block">Invoice/DR</label>
        <select wire:model="filterInvoice" class="rounded border px-3 py-2 text-sm w-full mt-2" required>
            <option value="">All Invoices/DR</option>
            @foreach ($invoiceOptions as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
    </div>

    <!-- Invoice Table -->
    <div class="overflow-auto rounded border border-gray-300 mt-3">
        <table class="w-full text-left text-sm text-gray-700">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Invoice Number</th>
                    <th class="px-4 py-2">Invoice Date</th>
                    <th class="px-4 py-2">Invoice Amount</th>
                    <th class="px-4 py-2">Remove</th>
                </tr>
            </thead>
            <tbody>
                @if ($filterCustomer)
                @foreach ($selectedInvoices as $index => $inv)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $inv['number'] }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($inv['date'])->format('F d, Y') }}</td>
                        <td class="px-4 py-2">₱{{ number_format($inv['amount'], 2) }}</td>
                        <td class="px-4 py-2 text-center">
                            <button wire:click="removeInvoice({{ $index }})" class="text-red-600 hover:underline">Remove</button>
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
            @else
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    No data found. Please select a customer.
                </td>
            </tr>
        @endif
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
        <input id="amount" type="number" min="0" step="0.01" wire:model.lazy="amount" class="border rounded px-3 py-2 w-full" required/>
    </div>

    <!-- Other Deduction -->
    <div class="mb-2">
        <label for="deduction" class="block mb-1 font-semibold">Other Deduction (optional)</label>
        <input id="deduction" type="number" min="0" step="0.01" wire:model.lazy="deduction" placeholder="₱0.00" class="border rounded px-3 py-2 w-full" required />
    </div>

     <!-- EWT Amount -->
     <div class="mb-2">
        <label for="ewt" class="block mb-1 font-semibold">EWT Amount</label>
        <input id="ewt" type="number" min="0" step="0.01" wire:model.lazy="ewt" placeholder="₱0.00" class="border rounded px-3 py-2 w-full" required />
    </div>

    <!-- Remarks -->
    <div class="mb-2">
        <label for="remarks" class="block mb-1 font-semibold">Remarks</label>
        <textarea id="remarks" wire:model="remarks" rows="3" class="border rounded px-3 py-2 w-full" placeholder="Add any notes here..."></textarea>
    </div>

    <!-- Check Fields -->
    @if ($paymentMethod === 'Check')
        <div class="space-y-4">
            <div>
                <label for="checkBank" class="block mb-1 font-semibold">Select Bank</label>
                <select id="checkBank" wire:model="checkBank" class="border rounded px-3 py-2 w-full">
                    <option value="" disabled>Select Bank</option>
                    <option>Bank A</option>
                    <option>Bank B</option>
                    <option>Bank C</option>
                </select>
            </div>
            <div>
                <label for="chequeNumber" class="block mb-1 font-semibold">Cheque Number</label>
                <input id="chequeNumber" type="text" wire:model="chequeNumber" class="border rounded px-3 py-2 w-full" />
            </div>
            <div>
                <label for="checkDate" class="block mb-1 font-semibold">Check Date</label>
                <input id="checkDate" type="date" wire:model="checkDate" class="border rounded px-3 py-2 w-full" />
            </div>
        </div>
    @endif

    <!-- Bank Transfer Fields -->
    @if ($paymentMethod === 'Bank Transfer')
        <div class="space-y-4">
            <div>
                <label for="transferBank" class="block mb-1 font-semibold">Select Bank</label>
                <select id="transferBank" wire:model="transferBank" class="border rounded px-3 py-2 w-full">
                    <option value="" disabled>Select Bank</option>
                    <option>Bank A</option>
                    <option>Bank B</option>
                    <option>Bank C</option>
                </select>
            </div>
            <div>
                <label for="referenceNumber" class="block mb-1 font-semibold">Reference Number</label>
                <input id="referenceNumber" type="text" wire:model="referenceNumber" class="border rounded px-3 py-2 w-full" />
            </div>
            <div>
                <label for="transactionDate" class="block mb-1 font-semibold">Transaction Date</label>
                <input id="transactionDate" type="date" wire:model="transactionDate" class="border rounded px-3 py-2 w-full" />
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
