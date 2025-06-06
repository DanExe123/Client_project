<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\ContactDeveloper;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\User\UserDashboard;
use App\Livewire\Admin\MasterFiles\CustomerMaster;
use App\Livewire\Admin\MasterFiles\ProductMaster;
use App\Livewire\Admin\MasterFiles\SupplierMaster;
use App\Livewire\Admin\MasterFiles\UserList;
// Purchasing dropdown 
use App\Livewire\Admin\Purchasing\PoToSupplier;
use App\Livewire\Admin\Purchasing\CustomerPoList;
use App\Livewire\Admin\Purchasing\PoToSupplierList;
use App\Livewire\Admin\Purchasing\CustomerPo;
use App\Livewire\Admin\Inventory\Recieving;
use App\Livewire\Admin\Inventory\ReturnByCustomer;


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
        Route::get('/recieving', Recieving::class)->name('admin.inventory.recieving');
        Route::get('/return-by-customer', ReturnByCustomer::class)->name('admin.inventory.return-by-customer');
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
