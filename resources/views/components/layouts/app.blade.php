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
    <p class="alert alert-warning text-black" wire:offline>
        Whoops, your device has lost connection. The web page you are viewing is offline.
    </p>

    <x-layouts.app.header :title="$title ?? null" />

    <div class="container mx-auto p-6">
        {{ $slot }}
    </div>
    @wireUiScripts
   
 @livewireScripts
</body>
</html>

