<x-modal-card title="Update returnbycustomer" name="edit">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-input label="Date" placeholder="Enter Date" />
        <x-input label="Supplier" placeholder="Enter Supplier" />
        <x-input label="Total" placeholder="Enter Total" />
    </div>

    <x-slot name="footer" class="flex justify-end gap-x-4">
        <div class="flex gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button primary label="Update" wire:click="save" />
        </div>
    </x-slot>
</x-modal-card>
