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
use App\Livewire\PaymentToSupplier;

use App\Livewire\Testform;
// customer add edit 
use App\Livewire\Addcustomer;
use App\Livewire\Addproduct;
use App\Livewire\Addsupplier;
use App\Livewire\Editcustomer;
use App\Livewire\Editproduct;
use App\Livewire\Editsupplier;
use App\Livewire\AdjustmentStockcard;
use App\Livewire\Expenses;
use App\Livewire\Editexpenses;
use App\Livewire\Recievingapproval;
use App\Livewire\ViewDetailRecieving;
use App\Livewire\Editrecieving;
use App\Livewire\ServeSaleReleasing;
use App\Livewire\ServePrintPreview;
use App\Livewire\ViewReceivingDetails;
use App\Livewire\Viewtransaction;
use App\Livewire\ViewMonthlyCashFlow;

use App\Livewire\LoginForm;

Route::get('/', CustomerMaster::class)->name('login-form');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Route::get('dashboard', AdminDashboard::class)->name('admin.dashboard');
        Route::get('/contact-developer', ContactDeveloper::class)->name('contact-developer');
        Route::get('/CustomerMaster', CustomerMaster::class)->name('customer-master');
        Route::get('/add-customer-files', Addcustomer::class)->name('addcustomer');
        Route::get('/customeredit/{id}', Editcustomer::class)->name('customeredit');
        Route::get('/edit-product/{id}', Editproduct::class)->name('productedit');
        Route::get('/edit-supplier/{id}', Editsupplier::class)->name('supplieredit');

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
        Route::get('/recievingapproval/{purchaseOrderId}', Recievingapproval::class)->name('recievingapproval');
        Route::get('/view-detail-recieving/{id}', ViewDetailRecieving::class)->name('view-detail-recieving');
        Route::get('/receiving/view-details/{id}', ViewReceivingDetails::class)->name('view-receiving-details');
        Route::get('/edit-recieving/{id}', Editrecieving::class)->name('editrecieving');
        Route::get('/serve-sales-releasing/{id}', ServeSaleReleasing::class)->name('serve-sale-releasing');
        Route::post('/sales-releasing/serve/{id}', [SalesReleasing::class, 'serve'])->name('sales-releasing.serve');
        Route::get('/sales-releasing/print-preview/{id}', [SalesReleasing::class, 'printPreview'])->name('serve-print-preview');
        Route::get('/recieving/cancel/{id}', Recieving::class)->name('recieving.cancel');




        Route::get('/return-by-customer', ReturnByCustomer::class)->name('return-by-customer');
        Route::get('/return-by-supplier', ReturnToSupplier::class)->name('return-by-supplier');
        Route::get('/stockcard', Stockcard::class)->name('stockcard');
        Route::get('/adjustments/{product}', AdjustmentStockcard::class)->name('adjustment-stockcard');

        Route::get('/expenses', Expenses::class)->name('expenses');
        Route::get('/expenses/{id}/edit', Editexpenses::class)->name('editexpenses');


        Route::get('/payment-to-supplier', PaymentToSupplier::class)->name('payment-to-supplier');
        Route::get('/sales-releasing', SalesReleasing::class)->name('sales-releasing');
        Route::get('/unservered-lacking', UnserveredLacking::class)->name('unservered-lacking');
        Route::get('/print/unserved', function () {
            $component = new \App\Livewire\UnserveredLacking();
            $component->mount(); // this already fetches the data as you said
            $data = $component->unservedData;

            return view('livewire.print-unservered', compact('data'));
        })->name('print-unservered');

        Route::get('/account-recievables', AccountRecievables::class)->name('account-recievables');
        Route::get('/view-transaction/{customer}', Viewtransaction::class)->name('viewtransaction');
        Route::get('/supplier/payables/{supplier}', \App\Livewire\ViewSupplierPayables::class)->name('view-supplier-payables');


        Route::get('/credit-debit', CreditDebit::class)->name('credit-debit');
        Route::get('/payment-application', PaymentApplication::class)->name('payment-application');
        Route::get('/account-payable', AccountPayable::class)->name('account-payable');
        Route::get('/payable-ledger', PayableLedger::class)->name('payable-ledger');
        Route::get('/sales-summary', SalesSummary::class)->name('sales-summary');
        Route::get('/sales-book', SalesBook::class)->name('sales-book');
        Route::get('/cash-flow', CashFlow::class)->name('cash-flow');
        Route::get('/cashflow/monthly', ViewMonthlyCashFlow::class)->name('view-monthly-cashflow');
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
