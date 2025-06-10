<div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6">
    {{-- Header --}}
    <div>
        <div class="flex justify-start">
            <h2 class="text-lg font-bold text-gray-800">Edit Product</h2>
        </div>

        {{-- Success Alert --}}
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="mt-2">
                <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                    class="!bg-green-300 !w-full" />
            </div>
        @endif
    </div>

    {{-- Breadcrumb --}}
    <div class="text-gray-500 flex text-start gap-3">
        <span class="text-gray-500 font-medium">Product Master</span>
        <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
        <span class="text-gray-500 font-medium">Edit Product</span>
    </div>
    <hr>

    {{-- Form --}}
    <form wire:submit.prevent="updateProduct" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <x-input label="Barcode" wire:model="barcode" id="barcode" placeholder="Enter barcode"
                :error="$errors->first('barcode')" />
            <x-input label="Supplier" wire:model="supplier" id="supplier" placeholder="Enter supplier name"
                :error="$errors->first('supplier')" />
            <x-input label="Product Description" wire:model="description" id="description"
                placeholder="Enter product description" :error="$errors->first('description')" />
            <x-input label="Highest UOM" wire:model="highest_uom" id="highest_uom" placeholder="Enter highest unit" />
            <x-input label="Lowest UOM" wire:model="lowest_uom" id="lowest_uom" placeholder="Enter lowest unit" />
            <x-input label="Price" wire:model="price" id="price" placeholder="Enter price" type="number" step="0.01"
                :error="$errors->first('price')" />
            <x-input label="Selling Price" wire:model="selling_price" id="selling_price" placeholder="Enter selling price" type="number" step="0.01"
                :error="$errors->first('selling_price')" />
        </div>
        <hr>
        <div class="pt-2 flex justify-center">
            <x-button spinner type="submit" primary label="Update" class="flex justify-center !w-48" />
        </div>
    </form>
</div>
