<x-modal-card title="Add Supplier" name="Add">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-input label="PO#" wire:model.defer="po_number" placeholder="Enter PO#" />
        <x-input label="Customer Name" wire:model.defer="customer_name" placeholder="Enter Customer Name" />
        <x-input label="Customer Address" wire:model.defer="customer_address" placeholder="Enter Address" />
        <x-input label="Terms (No. of Days)" wire:model.defer="terms_days" type="number" placeholder="Enter number of days" />
        <x-input label="Customer Contact Number" wire:model.defer="customer_contact_number" placeholder="Enter Contact Number" />
        <x-input label="Customer Contact Person" wire:model.defer="customer_contact_person" placeholder="Enter Contact Person" />
    </div>
    
    <x-slot name="footer" class="flex justify-end gap-x-4">
        <div class="flex gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button primary label="Save" wire:click="save" />
        </div>
    </x-slot>
    
    @if (session()->has('success'))
        <div class="mt-4 text-green-500">{{ session('success') }}</div>
    @endif
    
</x-modal-card>
