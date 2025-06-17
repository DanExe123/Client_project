<div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6 ">
    {{-- Success Alert --}}
    <div>
        <div class="flex justify-start">
            <h2 class="text-lg font-bold text-gray-800">Add Customer Files</h2>
        </div>
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="mt-2">
                <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                    class="!bg-green-300 !w-full" />
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                    class="mt-2">
                    <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                        class="!bg-green-300 !w-full" />
                </div>
        @endif
        </div>
        <div class="text-gray-500 flex text-start gap-3">
            <a class="text-gray-500 font-medium" wire:navigate href="{{ route('customer-master') }}">Customer
                Files</a>
            <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
            <span class="text-gray-500 font-medium "> Add Customer Files</span>
        </div>

        <hr>
        {{-- Form --}}
        <form wire:submit.prevent="submit" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-2 gap-4">
                <x-input label="Name" wire:model="name" id="name" placeholder="Enter your name"
                   x-on:input="$el.value = $el.value.replace(/[^a-zA-Z0-9\s,.-]/g, '')" :error="$errors->first('name')" />

                <x-input label="Email" wire:model="email" id="email" type="email" placeholder="Enter your email"
                    :error="$errors->first('email')" />

                <x-input label="Address" wire:model="address" id="address" placeholder="Enter your address"
                    x-on:input="$el.value = $el.value.replace(/[^a-zA-Z0-9\s,.#-]/g, '')"
                    :error="$errors->first('address')" />

                <x-input label="Contact Number" wire:model="contact" id="contact" type="text"
                    x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')" placeholder="Enter contact number"
                    :error="$errors->first('contact')" />

                <x-input label="Contact Person" wire:model="contact_person" id="contact_person"
                    placeholder="Enter contact person" x-on:input="$el.value = $el.value.replace(/[^a-zA-Z\s]/g, '')"
                    :error="$errors->first('contact_person')" />

                <x-input label="Term" wire:model="term" id="term" placeholder="Enter term"
                    x-on:input="$el.value = $el.value.replace(/[^0-9]/g, '')" :error="$errors->first('term')" />

                <x-input label="Tin Number" wire:model="cust_tin_number" id="cust_tin_number"
                    placeholder="Enter Customer Tin Number" x-on:input="$el.value = $el.value.replace(/[^0-9-]/g, '')"
                    :error="$errors->first('cust_tin_number')" />

            </div>
            <hr>
            <div class=" flex justify-center gap-6">
                <a href="{{ route('customer-master') }}">
                    <x-button label="Cancel" primary flat class="!text-sm mt-2" />
                </a>
                <div class="pt-2 flex justify-center ">
                    <x-button spinner type="submit" primary label="Submit" class="flex justify-center !w-48" />
                </div>
        </form>
    </div>