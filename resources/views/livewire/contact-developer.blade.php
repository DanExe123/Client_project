<div class="flex h-screen w-full items-center justify-center bg-gray-100 dark:bg-gray-900 p-4">
    <div class="relative w-full max-w-lg p-6 bg-white dark:bg-neutral-800 rounded-xl shadow-md border border-neutral-200 dark:border-neutral-700">
       
        <!-- Contact Developer Information -->
        <div class="mb-6 text-center text-gray-800 dark:text-white">
            <h2 class="text-xl font-semibold mb-2">Contact the Developer</h2>
            <p>If you have any concerns or problems with the system, feel free to reach out to the developer directly. Your feedback is important to improve the system.</p>
            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">You can send a message through the form below, and the developer will get back to you as soon as possible.</p>
        </div>

    <!-- Success Message -->
    @include('partials.toast')


        <!-- Contact Form -->
        <form wire:submit.prevent="submit" class="space-y-4">
            @csrf

            <!-- Name Input -->
            <div class="relative">
                <input type="text" wire:model="name"
                    class="peer block w-full rounded-lg border-2 border-gray-300 bg-transparent py-2 px-3 focus:border-blue-500 focus:outline-none"
                    placeholder="Name" />
                @error('name') 
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                @enderror
            </div>

            <!-- Email Input -->
            <div class="relative">
                <input type="email" wire:model="email"
                    class="peer block w-full rounded-lg border-2 border-gray-300 bg-transparent py-2 px-3 focus:border-blue-500 focus:outline-none"
                    placeholder="Email address" />
                @error('email') 
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div> 
                @enderror
            </div>

            <!-- Message Textarea -->
            <div x-data="{ message: '', resize: false }">
                <!-- Message Textarea -->
                <div class="relative mb-6">
                    <textarea 
                        x-model="message"
                        x-ref="textarea"
                        x-init="
                            $nextTick(() => {
                                $refs.textarea.style.height = $refs.textarea.scrollHeight + 'px'; 
                            })"
                        x-on:input="
                            $refs.textarea.style.height = 'auto'; 
                            $refs.textarea.style.height = $refs.textarea.scrollHeight + 'px';
                        "
                        wire:model="message"
                        class="peer block min-h-[auto] w-full rounded border-2 bg-transparent py-[0.32rem] px-3 leading-[1.6] outline-none transition-all duration-200 ease-linear focus:outline-none"
                        rows="3" 
                        placeholder="Message"
                    ></textarea>
                    
                    @error('message') 
                        <div class="text-red-500 text-xs mt-2">{{ $message }}</div> 
                    @enderror
                </div>
            <!-- Submit Button -->

            <button type="submit"
                class="w-full rounded-lg bg-[#357D7F] text-white py-2 px-4 text-sm font-medium uppercase shadow-md hover:bg-[#285d5f] transition">
                Send
            </button>
        </form>
    </div>
</div>
