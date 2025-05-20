@if (session()->has('success'))
<div
    x-cloak
    id="toast-default"
    class="fixed top-4 right-4 flex items-center max-w-xs p-4 text-gray-500 bg-white rounded-lg shadow-lg z-50 overflow-hidden"
    role="alert"
    x-data="{ show: false }"
    x-init="
        requestAnimationFrame(() => {
            show = true;
            setTimeout(() => show = false, 6000); // Hide after 6s
        });
    "
    x-show="show"
    x-transition:enter="transition ease-in-out duration-700 transform"
    x-transition:enter-start="translate-x-20 opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100"
    x-transition:leave="transition ease-in-out duration-500 transform"
    x-transition:leave-start="translate-x-0 opacity-100"
    x-transition:leave-end="translate-x-20 opacity-0"
>
    <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 text-blue-500 bg-blue-100 rounded-lg dark:bg-blue-800 dark:text-blue-200">
        <x-phosphor.icons::bold.seal-check class="w-4 h-4 text-green-500" />
        <span class="sr-only">Success</span>
    </div>
    <div class="ms-3 text-sm font-normal">
        {{ session('success') }}
    </div>
    <button type="button"
        class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
        @click="show = false" aria-label="Close">
        <span class="sr-only">Close</span>
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
        </svg>
    </button>
</div>
@endif
