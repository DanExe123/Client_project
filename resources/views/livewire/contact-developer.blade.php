<div>
    <div x-cloak class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex max-w-[335px] min-h-[400px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
            <div x-data="{ mode: 'mail' }" class="flex-1 p-6 lg:p-10 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-es-lg rounded-ee-lg lg:rounded-ss-lg lg:rounded-ee-none text-[13px] leading-[20px]">
    
                <!-- Icons -->
                <div class="flex justify-center gap-4 mb-4">
                    <button @click="mode = 'mail'">
                        <x-phosphor.icons::fill.envelope class="w-18 h-18 text-gray-700 hover:text-[#00e6e6]" />
                    </button>
                    <button @click="mode = 'phone'">
                        <x-phosphor.icons::fill.phone-call class="w-18 h-16 text-gray-700 hover:text-[#00e6e6]" />
                    </button>
                </div>
            
                <!-- MAIL FORM -->
                <div x-cloak x-show="mode === 'mail'" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                    <div class="text-center text-gray-800 dark:text-white">
                        <h2 class="text-xl font-semibold">Contact the Developer via Email</h2>
                        <p>If you have any concerns or problems with the system, feel free to reach out via the form below.</p>
                    </div>
            
                    <div class="flex flex-col gap-6 mt-4">
                        <form wire:submit.prevent="submit" class="space-y-4">
                            @csrf
                            <div class="relative">
                                <input type="text" wire:model="name" placeholder="Name"
                                    class="peer block w-full rounded-lg border-2 border-gray-300 bg-transparent py-2 px-3 focus:border-blue-500 focus:outline-none" />
                                @error('name') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
            
                            <div class="relative">
                                <input type="email" wire:model="email" placeholder="Email address"
                                    class="peer block w-full rounded-lg border-2 border-gray-300 bg-transparent py-2 px-3 focus:border-blue-500 focus:outline-none" />
                                @error('email') <div class="text-red-500 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
            
                            <div x-data="{ message: @entangle('message').defer }">
                                <div class="relative mb-6">
                                    <textarea
                                        x-model="message"
                                        x-ref="textarea"
                                        x-init="$nextTick(() => { $refs.textarea.style.height = $refs.textarea.scrollHeight + 'px'; })"
                                        x-on:input=" $refs.textarea.style.height = 'auto'; $refs.textarea.style.height = $refs.textarea.scrollHeight + 'px';"
                                        class="peer block min-h-[auto] w-full rounded border-2 bg-transparent py-[0.32rem] px-3"
                                        rows="3"
                                        placeholder="Message"
                                    ></textarea>
                                    @error('message') <div class="text-red-500 text-xs mt-2">{{ $message }}</div> @enderror
                                </div>
                            </div>
            
                            <x-button spinner type="submit" primary label="Submit" class="flex justify-center !w-full !bg-[#00e6e6]" />
                        </form>
                    </div>
                </div>
            
                <!-- PHONE FORM -->
                <div x-cloak x-show="mode === 'phone'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                >
                    <div class="text-center text-gray-800 dark:text-white">
                        <h2 class="text-xl font-semibold">Contact the Developer via Phone</h2>
                        <p>If urgent, you may contact the developer directly through the number below.</p>
                    </div>
            
                    <div class="mt-6 text-center text-lg font-semibold text-gray-800 dark:text-white">
                        📞 0961 739 3867
                        <p class="text-sm text-gray-600 dark:text-gray-400">Available Mon–Fri, 9am–5pm</p>
                    </div>
                </div>
            </div>


            <!-- RIGHT: Image Section -->
            <div x-cloak
            class="p-4 bg-white dark:bg-[#1D0002] relative lg:-ms-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-e-lg w-full lg:w-[438px] shrink-0 overflow-hidden h-[376px] lg:h-auto">
            <img src="{{ asset('logo/contact.jpg') }}" alt="Dnexus" class="w-full h-[500px] object-cover" />
            <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-e-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"></div>
        </div>        
        </main>
    </div>
</div>
