<x-modal-card title="Delete Confirmation" name="Delete">
    <div class="p-4 text-center text-gray-700 text-lg">
        Are you sure you want to delete this row?
    </div>

    <x-slot name="footer" class="flex justify-end gap-x-4">
        <x-button flat label="Cancel" x-on:click="close" />
        <x-button negative label="Delete" wire:click="deleteRow" />
    </x-slot>
</x-modal-card>
