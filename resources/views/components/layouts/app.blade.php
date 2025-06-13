<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('logo/logoweb1-removebg-preview.png') }}" type="image/png">
    <title>Inventory Management System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @wireUiStyles
    @livewireStyles
</head>
<body class="">
    
    <div 
    x-data="{ loading: true }"
    x-init="
        window.addEventListener('load', () => loading = false);
        Livewire.hook('message.sent', () => loading = true);
        Livewire.hook('message.processed', () => loading = false);
        setTimeout(() => loading = false, 5000);
    "
    x-show="loading"
    x-transition.opacity.duration.700ms
    class="fixed inset-0 flex items-center justify-center bg-white bg-opacity-80 z-[9999]"
>
    <div class="flex flex-col items-center space-y-4">
        <!-- Checklist GIF -->
        <img 
        src="{{ asset('logo/6416398.gif') }}" 
            alt="Animated checklist" 
            class="w-24 h-24"
        />
        <p class="text-gray-700 text-lg font-semibold animate-pulse">
            Loading Inventory System...
        </p>
    </div>
</div>



    <x-layouts.app.header :title="$title ?? null" />

    <div class="container mx-auto p-6">
        {{ $slot }}
    </div>
    @wireUiScripts
   
 @livewireScripts
</body>
</html>

