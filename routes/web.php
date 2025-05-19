<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
Use App\Livewire\ContactDeveloper;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\User\UserDashboard;
Use App\Livewire\Admin\MasterFiles\CustomerMaster;
Use App\Livewire\Admin\MasterFiles\ProductMaster;
Use App\Livewire\Admin\MasterFiles\SupplierMaster;
Use App\Livewire\Admin\MasterFiles\UserList;


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
       // Route::get('dashboard', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/contact-developer', ContactDeveloper::class)->name('contact-developer');
        Route::get('/CustomerMaster', CustomerMaster::class)->name('admin.masterfiles.customer-master');
        Route::get('/ProductMaster', ProductMaster::class)->name('admin.masterfiles.product-master');
        Route::get('/SupplierMaster', SupplierMaster::class)->name('admin.masterfiles.supplier-master');
        Route::get('/UserList', UserList::class)->name('admin.masterfiles.user-list');
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
