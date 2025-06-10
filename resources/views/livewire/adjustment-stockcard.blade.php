<div>
    <div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6 ">
        {{-- Success Alert --}}
    
    
    
        <div>
            <div class="flex justify-start">
                <h2 class="text-lg font-bold text-gray-800">Add Adjustment</h2>
            </div>
    
    
            @if (session()->has('message'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                    class="mt-2">
                    <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                        class="!bg-green-300 !w-full" />
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                        class="mt-2">
                        <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                            class="!bg-green-300 !w-full" />
                    </div>
            @endif
            </div>
    
            <div class="text-gray-500 flex text-start gap-3">
                <a class="text-gray-500 font-medium" wire:navigate href="{{ route('stockcard') }}"> Stockcard</a>
                <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
                <span class="text-gray-500 font-medium "> Adjustment</span>
            </div>
        <hr>
        {{-- Form --}}
            
        <form wire:submit.prevent="submitQuantity" class="space-y-6">
    
            {{-- Since we only have one product, we can reference it directly --}}
            @php
                $id   = $product->id;
                $data = $productsData[$id];
            @endphp
        
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        
                {{-- Read-only fields populated in mount() --}}
                <x-input 
                    label="Barcode"
                    wire:model.defer="productsData.{{ $id }}.barcode"
                    readonly
                />
        
                <x-input 
                    label="Product Name"
                    wire:model.defer="productsData.{{ $id }}.productName"
                    readonly
                />
        
                <x-input 
                    label="Bag (Highest UoM)"
                    wire:model.defer="productsData.{{ $id }}.highestUom"
                    readonly
                />
        
                {{-- Editable fields --}}
                <x-input 
                    label="PC"
                    wire:model.defer="productsData.{{ $id }}.lowest_uom"
                    type="number"
                    readonly />
        
                <x-input 
                    label="Damages"
                    wire:model.defer="productsData.{{ $id }}.damages"
                    type="number"
                />
        
                <x-input 
                    type="number"
                    label="Quantity"
                    wire:model.defer="productsData.{{ $id }}.quantity"
                    min="1"
                />
        
            </div>
        
            <hr class="my-4" />
        
            <div class="flex justify-end gap-4">
                <a href="{{ route('stockcard') }}">
                    <x-button flat label="Cancel" class="!text-sm" />
                </a>
        
                <x-button spinner type="submit" primary label="Submit" class="!w-48" />
            </div>
        </form>
        
      
    </div>
    
</div>
