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
// Purchasing dropdown 
Use App\Livewire\Admin\Purchasing\PoToSupplier;
Use App\Livewire\Admin\Purchasing\CustomerPoList;
Use App\Livewire\Admin\Purchasing\PoToSupplierList;
Use App\Livewire\Admin\Purchasing\CustomerPo;

Use App\Livewire\LoginForm;

Route::get('/', CustomerMaster::class)->name('login-form');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
       // Route::get('dashboard', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/contact-developer', ContactDeveloper::class)->name('contact-developer');
        Route::get('/CustomerMaster', CustomerMaster::class)->name('admin.masterfiles.customer-master');
        Route::get('/ProductMaster', ProductMaster::class)->name('admin.masterfiles.product-master');
        Route::get('/SupplierMaster', SupplierMaster::class)->name('admin.masterfiles.supplier-master');
        Route::get('/UserList', UserList::class)->name('admin.masterfiles.user-list');
        // purchasing dropdown //
        Route::get('/po-to-supplier', PoToSupplier::class)->name('admin.purchasing.po-to-supplier');
        Route::get('/customer-po-list', CustomerPoList::class)->name('admin.purchasing.customer-po-list');
        Route::get('/po-to-supplier-list', PoToSupplierList::class)->name('admin.purchasing.po-to-supplier-list');
        Route::get('/customer-po', CustomerPo::class)->name('admin.purchasing.customer-po');
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
