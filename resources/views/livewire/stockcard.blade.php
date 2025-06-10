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
            x-on:click=" $wire.openAdjustmentsModal() "
            :class="count($selectedProductId) > 0 ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-300 text-white cursor-not-allowed'"
            :disabled="count($selectedProductId) === 0"
        >
            Adjustments
        </x-button>
        
            <x-modal-card title="Adjustments" name="Adjustments">
                @foreach($products as $product)
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-input 
                        label="Barcode" 
                        placeholder="Barcode" 
                        wire:model="barcode" 
                        value="{{ $product->barcode ?? '' }}" readonly 
                    />
                
                    <x-input 
                        label="Product Name" 
                        placeholder="Product Name" 
                        wire:model="productName" 
                        value="{{ $product->description ?? '' }}" readonly
                    />
                
                    <x-input 
                        label="Bag (Highest UoM)" 
                        placeholder="Bag (Highest UoM)" 
                        wire:model="highestUom" 
                        value="{{ $product->highest_uom ?? '' }}" readonly
                    />
                
                    <x-input 
                        label="PC" 
                        placeholder="PC" 
                        wire:model="pc" 
                        value="{{ $product->pc ?? '' }}" 
                    />
                
                    <x-input 
                        label="Damages" 
                        placeholder="Damages" 
                        wire:model="damages" 
                        value="{{ $product->damages ?? '' }}" 
                    />
                
                    <x-input 
                        type="number" 
                        label="Quantity" 
                        placeholder="Quantity" 
                        wire:model="quantity" 
                        
                    />
                </div>
                @endforeach
             
                <x-slot name="footer" class="flex justify-end gap-x-4">
            
             
                    <div class="flex gap-x-4">
                        <x-button flat label="Cancel" x-on:click="close" />
                        <x-button primary label="Save" wire:click="save" />
                    </div>
                </x-slot>
            </x-modal-card>
        </div>
    
        <div class="overflow-auto rounded-lg border border-gray-200">
            <table class="min-w-[1000px] w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-4 font-medium text-gray-900">
                            <input type="checkbox" />
                        </th>
                        <th class="px-4 py-4 font-medium text-gray-900">Barcode</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Product Name</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Bag (Highest UoM)</th>
                        <th class="px-4 py-4 font-medium text-gray-900">PC</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Damages</th>
                        <th class="px-4 py-4 font-medium text-gray-900">Quantity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @foreach($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-4"><input type="checkbox" /></td>
                        <td class="px-4 py-4">{{ $product->barcode }}</td>
                        <td class="px-4 py-4">{{ $product->description }}</td>
                        <td class="px-4 py-4">{{ $product->highest_uom }}</td>
                        <td class="px-4 py-4">{{ $product->pc }}</td>
                        <td class="px-4 py-4">{{ $product->damages }}</td>
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
