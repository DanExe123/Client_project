<div
     
     x-cloak>

    <!-- Header + Button -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-semibold">Customer Receivables</h2>
        <x-button primary label="View Customer Receivable" x-on:click="showModal = true" />
    </div>

    <!-- Search Bar -->
    <div class="w-full sm:max-w-xs relative mb-4">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
        </span>
        <input
            type="text"
            x-model="search"
            placeholder="Search by customer..."
            class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
        />
    </div>

    <!-- Modal -->
    <div 
        x-show="showModal" 
        class="fixed inset-0 z-50 flex justify-center items-start pt-10  bg-opacity-30" 
        x-transition
    >
        <div @click.away="showModal = false" class="bg-white rounded-lg shadow-lg w-full max-w-6xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold">Transactions</h2>
                <button @click="showModal = false" class="text-gray-600 hover:text-black text-xl">&times;</button>
            </div>

            <div class="overflow-auto max-h-[70vh]">
               
                       
            </div>
        </div>
    </div>

    <!-- Customer Table -->
    <div class="overflow-auto rounded-lg border border-gray-200 mt-6">
        <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-700">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr>
                    <th class="px-4 py-3 font-medium text-gray-900">Customer Name</th>
                    <th class="px-4 py-3 font-medium text-gray-900">Customer Term</th>
                    <th class="px-4 py-3 font-medium text-gray-900">Balance</th>
                    <th class="px-4 py-3 font-medium text-gray-900 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                @forelse ($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $customer->name }}</td>
                        <td class="px-4 py-3">{{ $customer->term }}</td>
                        <td class="px-4 py-3">
                            ₱{{ number_format($customer->releasedItems->sum('total_amount'), 2) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('viewtransaction', ['customer' => $customer->id]) }}">
                                <x-button 
                                    label="View Transaction" 
                                    primary 
                                />
                            </a>                                                   
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4 text-gray-500">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Controls -->
    <div class="mt-4">
        {{ $customers->links() }}
    </div>
</div>
