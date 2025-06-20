<div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6">
    <div>
        <div class="flex justify-start">
            <h2 class="text-lg font-bold text-gray-800">Add Supplier</h2>
        </div>
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="mt-2">
                <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                    class="!bg-green-300 !w-full" />
            </div>
        @endif
        @if (session()->has('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
            class="mt-2">
            <x-alert :title="session('error')" icon="check-circle" color="success" positive flat
                class="!bg-red-300 !w-full" />
        </div>
    @endif



    </div>
    <div class="text-gray-500 flex text-start gap-3">
        <span class="text-gray-500 font-medium">Suppliers</span>
        <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
        <span class="text-gray-500 font-medium">Add Supplier</span>
    </div>
    <hr>
    <form wire:submit.prevent="submit" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

            <x-input label="Supplier Name" wire:model="name" id="name" placeholder="Enter supplier name"
                x-on:input="$el.value = $el.value.replace(/[^a-zA-Z0-9\s]/g, '')"
                :error="$errors->first('name')" />

            <x-input label="Email" wire:model="email" id="email" type="email" placeholder="Enter your email"
                    :error="$errors->first('email')" />

            <x-input label="Address" wire:model="address" id="address" placeholder="Enter address"
                x-on:input="$el.value = $el.value.replace(/[^a-zA-Z0-9\s,.-]/g, '')"
                :error="$errors->first('address')" />

            <x-input label="Terms (No. of Days)" wire:model="term" id="term" placeholder="Enter term"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')" :error="$errors->first('term')" />

            <x-input label="Tin Number" wire:model="tin_number" id="tin_number" placeholder="Enter Tin number"
                x-on:input="$el.value = $el.value.replace(/[^0-9-]/g, '')" :error="$errors->first('tin_number')" />

                <x-input
                label="Contact Number"
                wire:model="contact"
                id="contact"
                placeholder="Enter contact number"
                x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')"
                :error="$errors->first('contact')"
            />
            
            @error('contact')
                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
            @enderror
            
            
            <x-input label="Contact Person" wire:model="contact_person" id="contact_person"
                placeholder="Enter contact person" x-on:input="$el.value = $el.value.replace(/[^a-zA-Z\s]/g, '')"
                :error="$errors->first('contact_person')" />

        </div>
        <hr>
        <div class=" flex justify-center gap-6">
            <a href="{{ route('supplier-master') }}">
                <x-button label="Cancel" primary flat class="!text-sm mt-2" />
            </a>
            <x-button spinner type="submit" primary label="Submit" class="flex justify-center !w-48" />
        </div>
    </form>
</div>