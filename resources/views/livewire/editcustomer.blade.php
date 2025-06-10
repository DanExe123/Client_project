<div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6">
    <div>
        <div class="flex justify-start">
            <h2 class="text-lg font-bold text-gray-800">Edit Customer</h2>
        </div>
    </div>

    <div class="text-gray-500 flex text-start gap-3">
        <a class="text-gray-500 font-medium" wire:navigate href="{{ route('customer-master') }}">Customer Files</a>
        <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
        <span class="text-gray-500 font-medium "> Edit Customer</span>
    </div> 
    <hr>

    <form wire:submit.prevent="updateCustomer" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <x-input label="Name" wire:model="name" id="name" placeholder="Enter your name" :error="$errors->first('name')" />

            <x-input label="Email" wire:model="email" id="email" type="email" placeholder="Enter your email" :error="$errors->first('email')" />

            <x-input label="Address" wire:model="address" id="address" placeholder="Enter your address" />

            <x-input label="Contact Number" wire:model="contact" id="contact" placeholder="Enter contact number" />

            <x-input label="Contact Person" wire:model="contact_person" id="contact_person" placeholder="Enter contact person" />

            <x-input label="Term" wire:model="term" id="term" placeholder="Enter term" />
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" wire:model="status"
                    class="mt-1 block w-full h-9 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>
        <hr>
        <div class="pt-2 flex justify-center gap-6">
            <a href="{{ route('customer-master') }}">
                <x-button label="Cancel" primary flat class="!text-sm" />
            </a>
            <x-button spinner type="submit" primary label="Update" class="flex justify-center !w-48" />
        </div>
    </form>
</div>
