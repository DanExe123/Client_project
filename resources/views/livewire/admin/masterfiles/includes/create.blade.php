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
            <form wire:submit.prevent="saveCustomer">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-input label="Name" wire:model="name" placeholder="Your full name" wire:model="name" />
                    <x-input label="Email" placeholder="Email" wire:model="email" />
                    <x-input label="Address" placeholder="Address" wire:model="address" />
                    <x-input label="Contact" placeholder="Contact" wire:model="contact"/>
                    <x-input label="Contact Person" placeholder="Contact Person" wire:model="contact_person" />
                    <x-input label="Term" placeholder="Term" wire:model="term" />
                </div>
                
                <div class="flex justify-end gap-x-4">     
                    <x-button flat label="Cancel" @click="open = false" />
                    <x-button primary type="submit" label="Save" />
                </div>
            </form>
        </div>
    </div>
</div>