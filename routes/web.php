<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Livewire\ContactDeveloper;
use App\Livewire\AdminDashboard;
use App\Livewire\UserDashboard;
use App\Livewire\CustomerMaster;
use App\Livewire\ProductMaster;
use App\Livewire\SupplierMaster;
use App\Livewire\UserList;

use App\Livewire\PoToSupplier;
use App\Livewire\CustomerPoList;
use App\Livewire\PoToSupplierList;
use App\Livewire\CustomerPo;
use App\Livewire\Recieving;
use App\Livewire\ReturnByCustomer;
use App\Livewire\ReturnToSupplier;
use App\Livewire\Stockcard;
use App\Livewire\SalesReleasing;
use App\Livewire\UnserveredLacking;
use App\Livewire\AccountRecievables;
use App\Livewire\CreditDebit;
use App\Livewire\PaymentApplication;
use App\Livewire\AccountPayable;
use App\Livewire\PayableLedger;
use App\Livewire\SalesSummary;
use App\Livewire\SalesBook;
use App\Livewire\CashFlow;


use App\Livewire\Testform;
// customer add edit 
use App\Livewire\Addcustomer;
use App\Livewire\Addproduct;
use App\Livewire\Addsupplier;
use App\Livewire\Editcustomer;

use App\Livewire\LoginForm;

Route::get('/', CustomerMaster::class)->name('login-form');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Route::get('dashboard', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/contact-developer', ContactDeveloper::class)->name('contact-developer');
        Route::get('/CustomerMaster', CustomerMaster::class)->name('customer-master');
        Route::get('/add-customer-files', Addcustomer::class)->name('addcustomer');
        Route::get('/customeredit/{id}', Editcustomer::class)->name('customeredit');
        Route::get('/add-product', Addproduct::class)->name('addproduct');
        Route::get('/add-supplier', Addsupplier::class)->name('addsupplier');
        Route::get('/ProductMaster', ProductMaster::class)->name('product-master');
        Route::get('/SupplierMaster', SupplierMaster::class)->name('supplier-master');
        Route::get('/UserList', UserList::class)->name('user-list');

        Route::get('/po-to-supplier', PoToSupplier::class)->name('po-to-supplier');
        Route::get('/customer-po-list', CustomerPoList::class)->name('customer-po-list');
        Route::get('/po-to-supplier-list', PoToSupplierList::class)->name('po-to-supplier-list');
        Route::get('/customer-po', CustomerPo::class)->name('customer-po');
        Route::get('/recieving', Recieving::class)->name('recieving');
        Route::get('/return-by-customer', ReturnByCustomer::class)->name('return-by-customer');
        Route::get('/return-by-supplier', ReturnToSupplier::class)->name('return-by-supplier');
        Route::get('/stockcard', Stockcard::class)->name('stockcard');
        Route::get('/sales-releasing', SalesReleasing::class)->name('sales-releasing');
        Route::get('/unservered-lacking', UnserveredLacking::class)->name('unservered-lacking');
        Route::get('/account-recievables', AccountRecievables::class)->name('account-recievables');
        Route::get('/credit-debit', CreditDebit::class)->name('credit-debit');
        Route::get('/payment-application', PaymentApplication::class)->name('payment-application');
        Route::get('/account-payable', AccountPayable::class)->name('account-payable');
        Route::get('/payable-ledger', PayableLedger::class)->name('payable-ledger');
        Route::get('/sales-summary', SalesSummary::class)->name('sales-summary');
        Route::get('/sales-book', SalesBook::class)->name('sales-book');
        Route::get('/cash-flow', CashFlow::class)->name('cash-flow');
        Route::get('/testform', Testform::class)->name('testform');
    });
});

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::prefix('user')->group(function () {

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

require __DIR__ . '/auth.php';
