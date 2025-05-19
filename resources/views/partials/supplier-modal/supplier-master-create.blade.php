<x-modal-card title="Add Supplier" name="Add">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-input label="Customer Name" placeholder="Enter Customer Name" />
        <x-input label="Customer Address" placeholder="Enter Address" />
        <x-input label="Terms (No. of Days)" type="number" placeholder="Enter number of days" />
        <x-input label="Customer Contact Number" placeholder="Enter Contact Number" />
        <x-input label="Customer Contact Person" placeholder="Enter Contact Person" />
    </div>

    <x-slot name="footer" class="flex justify-end gap-x-4">
        <div class="flex gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button primary label="Save" wire:click="save" />
        </div>
    </x-slot>
</x-modal-card>
