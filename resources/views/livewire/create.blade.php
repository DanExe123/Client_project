<div x-data="{ open: false }" @keydown.escape.window="open = false">
    <!-- Button to open modal -->
    <x-button emerald right-icon="plus" @click="open = true" />

    <!-- Modal backdrop -->
    <div
        x-show="open"
        class="fixed inset-0 flex items-center justify-center"
        style="display: none;"
    >
        <!-- Modal content -->
        <div
            x-show="open"
            @click.away="open = false"
            class="bg-white p-6 rounded shadow-lg max-w-lg w-full"
            style="display: none;"
        >
            <h2 class="text-lg font-semibold mb-4">Create Customer</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <form wire:submit.prevent="submit" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <input id="name" type="text" wire:model="name" placeholder="Name" />
                    <input id="email" type="text" wire:model="email" placeholder="Email" />
                    <input id="address" type="text" wire:model="address" placeholder="Address" />
                    <input id="contact" type="text" wire:model="contact" placeholder="Contact" />
                    <input id="contact_person" type="text" wire:model="contact_person" placeholder="Contact Person" />
                    <input id="term" type="text" wire:model="term" placeholder="Term" />
                
                    <div class="flex justify-end gap-x-4 mt-4 col-span-2">
                        <x-button flat label="Cancel" @click="open = false" type="button" />
                        <x-button type="submit" label="Save" />
                    </div>
                </form>
                
        </div>
    </div>
</div>
