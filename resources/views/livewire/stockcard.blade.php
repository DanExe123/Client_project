<div x-cloak>
    <div x-cloak class="space-y-2">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-900">Stock Card</h2>
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="mt-2">
                <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                    class="!bg-green-300 !w-full" />
            </div>
        @endif
        <!-- Action Buttons -->
        <div class="flex gap-2 justify-between flex-wrap">
            <!-- Search Bar -->
            <div class="w-full sm:max-w-xs relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
                </span>
                <input type="text" x-model="search" placeholder="Search by barcode or name..."
                    class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500" />
            </div>


            <x-button left-icon="wrench" interaction="primary" wire:click="goToAdjustment"
                :class="count($selectedProductId ?? []) !== 1
        ? 'bg-gray-300 text-white cursor-not-allowed'
        : 'bg-[#12ffac] hover:bg-[#13eda1] text-white'" :disabled="count($selectedProductId ?? []) !== 1">
                Adjustments
            </x-button>


            @foreach($products as $product)

            @endforeach


        </div>

    </div>

    <div class="overflow-auto rounded-lg border border-gray-200 mt-2">
        <table class="min-w-[1000px] w-full border-collapse bg-white text-left text-sm text-gray-500">
            <thead class="bg-gray-50 sticky top-0 z-10">
                @foreach($products as $product)
                    <tr>
                        <th class="px-4 py-4 font-medium text-gray-900">
                            <input type="checkbox" wire:click="toggleSelectAll"
                                @if($product->pluck('id')->diff($selectedProductId)->isEmpty()) checked @endif />
                        </th>
                        <th class="px-4 py-4 font-medium text-gray-900">Barcode</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Product Name</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Bag (Highest UoM)</th>
                        <th class="px-4 py-4 font-medium text-gray-900">PC</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Damages</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Quantity</th>
                    </tr>
                @endforeach
            </thead>
            <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4">
                            <input type="checkbox" wire:click="selectedProduct({{ $product->id }})"
                                @if(in_array($product->id, $selectedProductId)) checked @endif />
                        </td>
                        <td class="px-4 py-4">{{ $product->barcode }}</td>
                        <td class="px-4 py-4">{{ $product->description }}</td>
                        <td class="px-4 py-4">{{ $product->highest_uom }}</td>
                        <td class="px-4 py-4">{{ $product->lowest_uom }}</td>
                        <td class="px-4 py-4">{{ $product->damages }}</td>
                        <td class="px-4 py-4">{{ $product->quantity }}</td>
                    </tr>
                @endforeach
                @if ($products->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-500">No stock records found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>


    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('stockCardUI', () => ({
                search: '',
                products: [
                    { barcode: '1234567890', name: 'Dog Food Premium', bag: 20, pc: 10, damages: 0 },
                    { barcode: '0987654321', name: 'Cat Litter', bag: 15, pc: 5, damages: 1 },
                    // Add more sample data as needed
                ],
                get filteredProducts() {
                    const term = this.search.toLowerCase();
                    return this.products.filter(p =>
                        p.barcode.includes(term) ||
                        p.name.toLowerCase().includes(term)
                    );
                }
            }));
        });
    </script>

</div>