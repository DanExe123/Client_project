<x-modal-card title="Edit Customer" name="Edit">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-input label="Name" placeholder="Your full name" />
        <x-input label="Status" placeholder="Status" />
        <x-input label="Address" placeholder="Address" />
        <x-input label="Contact" placeholder="Contact" />
        <x-input label="Contact Person" placeholder="Contact Person" />
        <x-input label="Term" placeholder="Term" />
    </div>
 
    <x-slot name="footer" class="flex justify-end gap-x-4">

 
        <div class="flex gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
 
            <x-button primary label="Update" wire:click="save" />
        </div>
    </x-slot>
</x-modal-card>