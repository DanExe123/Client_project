<div x-cloak>
  <div x-data="payableLedger()" class=" w-full mx-auto">
    <!-- Header + Button -->
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-xl font-semibold">Payable Ledger</h2>
    </div>

    <!-- Search Bar -->
    <div class="w-full sm:max-w-xs relative mb-4">
      <span class="absolute inset-y-0 left-0 flex items-center pl-3">
        <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
      </span>
      <input type="text" x-model="search" placeholder="Search by customer..."
        class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
    </div>
    <div class="overflow-auto rounded-lg border border-gray-200">
      <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-700">
        <thead class="bg-gray-50 sticky top-0 z-10">
          <tr>
            <th class="px-4 py-3 font-large text-gray-900">Supplier Name</th>
            <th class="px-4 py-3 font-large text-gray-900">Term to Supplier</th>
            <th class="px-4 py-3 font-large text-gray-900">Balance</th>
            <th class="px-4 py-3 font-large text-gray-900">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 border-t border-gray-100">
          @foreach ($suppliers as $supplier)
        <tr class="hover:bg-gray-50">
        <td class="px-4 py-2">{{ $supplier->name }}</td>
        <td class="px-4 py-2">{{ $supplier->term }}</td>
        <td class="px-4 py-2 text-green-700 font-medium">
          ₱{{ number_format($supplier->receiveditem_sum_grand_total ?? 0, 2) }}
        </td>
        <td class="px-4 py-2">
          <a href="{{ route('view-supplier-payables', ['supplier' => $supplier->id]) }}">
          <x-button label="View Supplier Payables" primary />
          </a>
        </td>
        </tr>
      @endforeach
          @if ($suppliers->isEmpty())
        <tr>
        <td colspan="7" class="text-center p-4 text-gray-500">No suppliers found.</td>
        </tr>
      @endif
        </tbody>
      </table>
    </div>
  </div>
</div>