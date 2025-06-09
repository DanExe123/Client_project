<div>
    <div x-cloak x-data="stockCardUI" class="space-y-2">
        <!-- Title -->
        <h2 class="text-2xl font-semibold text-gray-900">Stock Card</h2>
    
        <!-- Action Buttons -->
        <div class="flex gap-2 justify-between flex-wrap">
            <!-- Search Bar -->
            <div class="w-full sm:max-w-xs relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <x-phosphor.icons::bold.magnifying-glass class="w-4 h-4 text-gray-500" />
                </span>
                <input
                    type="text"
                    x-model="search"
                    placeholder="Search by barcode or name..."
                    class="w-full pl-10 rounded-md border border-gray-300 px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                />
            </div>
    
            <!-- Adjustments Button -->
            <x-button
                left-icon="wrench"
                interaction="primary"
                x-on:click="$openModal('Adjustments')"
                class="bg-blue-600 hover:bg-blue-700 text-white"
            >
                Adjustments
            </x-button>

            <x-modal-card title="Adjustments" name="Adjustments">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-input label="Barcode" placeholder="Barcode" />
                    <x-input label="Product Name" placeholder="Product Name" />
                    <x-input label="Bag (Highest UoM)" placeholder="Bag (Highest UoM)" />
                    <x-input label="PC" placeholder="PC" />
                    <x-input label="Damages" placeholder="Damages" />
                </div>
             
                <x-slot name="footer" class="flex justify-end gap-x-4">
            
             
                    <div class="flex gap-x-4">
                        <x-button flat label="Cancel" x-on:click="close" />
                        <x-button primary label="Save" wire:click="save" />
                    </div>
                </x-slot>
            </x-modal-card>
        </div>
    
        <!-- Table -->
        <div class="overflow-auto rounded-lg border border-gray-200">
            <table class="min-w-[1000px] w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-4 font-medium text-gray-900">Barcode</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Product Name</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Bag (Highest UoM)</th>
                        <th class="px-4 py-4 font-medium text-gray-900">PC</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Damages</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    <template x-for="(product, index) in filteredProducts" :key="index">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4" x-text="product.barcode"></td>
                            <td class="px-4 py-4" x-text="product.name"></td>
                            <td class="px-4 py-4" x-text="product.bag"></td>
                            <td class="px-4 py-4" x-text="product.pc"></td>
                            <td class="px-4 py-4" x-text="product.damages"></td>
                        </tr>
                    </template>
                    <tr x-show="filteredProducts.length === 0">
                        <td colspan="5" class="text-center p-4 text-gray-500">No stock records found.</td>
                    </tr>
                </tbody>
            </table>
        </div>
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
