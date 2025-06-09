<div class="max-w-xl mx-auto space-y-6 border border-gray-200">

    {{-- Success Alert --}}
    @if (session()->has('message'))
        <x-alert :title="session('message')" icon="check-circle" color="success" positive flat/>
    @endif
    {{-- Form --}}
    <form wire:submit.prevent="submit" class="space-y-4">

        <x-input
            label="Name"
            wire:model="name"
            id="name"
            placeholder="Enter your name"
            :error="$errors->first('name')"
        />

        <x-input
            label="Email"
            wire:model="email"
            id="email"
            type="email"
            placeholder="Enter your email"
            :error="$errors->first('email')"
        />

        <x-input
            label="Address"
            wire:model="address"
            id="address"
            placeholder="Enter your address"
        />

        <x-input
            label="Contact Number"
            wire:model="contact"
            id="contact"
            placeholder="Enter contact number"
        />

        <x-input
            label="Contact Person"
            wire:model="contact_person"
            id="contact_person"
            placeholder="Enter contact person"
        />

        <x-input
            label="Term"
            wire:model="term"
            id="term"
            placeholder="Enter term"
        />

        <div class="pt-4">
            <x-button spinner type="submit" primary label="Submit" />
        </div>

    </form>
</div>
