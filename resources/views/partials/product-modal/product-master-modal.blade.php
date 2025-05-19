<x-modal-card title="Add Product" name="Add">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-input label="Barcode" placeholder="Enter Barcode" />
        <x-input label="Supplier" placeholder="Enter Supplier Name" />
        <x-input label="Product Description" placeholder="Enter Description" />
        <x-input label="Highest Unit of Measurement" placeholder="e.g. Box, Pack" />
        <x-input label="Lowest Unit of Measurement" placeholder="e.g. Piece, Tablet" />
        <x-input label="Price" type="number" placeholder="Enter Price" />
    </div>

    <x-slot name="footer" class="flex justify-end gap-x-4">
        <div class="flex gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button primary label="Save" wire:click="save" />
        </div>
    </x-slot>
</x-modal-card>
