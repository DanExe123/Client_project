<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
Use App\Livewire\ContactDeveloper;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\User\UserDashboard;


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('dashboard', AdminDashboard::class)->name('admin.dashboard');
    });
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('dashboard', UserDashboard::class)->name('user.dashboard');
    });
});


Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/contact-developer', ContactDeveloper::class)->name('contact-developer');

















Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
