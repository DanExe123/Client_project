<x-modal-card title="Add Recieving" name="Add">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <x-input label="PO#" placeholder="Enter PO#" />
        <x-input label="Customer " placeholder="Enter Customer" />
        <x-input label="Date" type="date" placeholder="Enter date" />
        <x-input label="Status" placeholder="Enter status" />
        <x-input label="Total" placeholder="Enter Total" />
    </div>

    <x-slot name="footer" class="flex justify-end gap-x-4">
        <div class="flex gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />
            <x-button  primary label="Save" wire:click="save" />
        </div>
    </x-slot>
</x-modal-card>
