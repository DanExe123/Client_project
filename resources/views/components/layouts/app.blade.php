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
    x-init="window.addEventListener('load', () => setTimeout(() => loading = false, 2000))" 
    x-show="loading" 
    x-transition.opacity.duration.700ms 
    class="fixed inset-0 flex items-center justify-center bg-white bg-opacity-90 z-[9999]"
>
    <div class="flex flex-col items-center space-y-4">
        <!-- Inventory-style Animated Loader -->
        <div class="relative w-24 h-24">
            <div class="absolute inset-0 border-4 border-dashed border-blue-500 rounded-full animate-spin"></div>
            <div class="absolute top-2 left-2 w-4 h-4 bg-blue-500 rounded-full animate-bounce"></div>
            <div class="absolute bottom-2 right-2 w-4 h-4 bg-green-500 rounded-full animate-bounce delay-150"></div>
        </div>

        <p class="text-gray-700 text-lg font-semibold animate-pulse">Loading Inventory System...</p>
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

