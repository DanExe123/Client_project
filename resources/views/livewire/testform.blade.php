<div>
    <div>
        @if (session()->has('message'))
            <div class="mb-4 text-green-600">
                {{ session('message') }}
            </div>
        @endif
    
        <form wire:submit.prevent="submit">
            <div>
                <label for="name">Name</label><br>
                <input id="name" type="text" wire:model="name" />
                @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
    
            <div class="mt-2">
                <label for="email">Email</label><br>
                <input id="email" type="email" wire:model="email" />
                @error('email') <span class="text-red-600">{{ $message }}</span> @enderror
            </div>
    
            <input id="address" type="text" wire:model="address" placeholder="Address" />
            <input id="contact" type="text" wire:model="contact" placeholder="Contact" />
        <input id="contact_person" type="text" wire:model="contact_person" placeholder="Contact Person" />
        <input id="term" type="text" wire:model="term" placeholder="Term" />

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    
</div>
