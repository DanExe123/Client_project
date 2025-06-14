<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\CustomerReturn;
use App\Models\CustomerReturnItem;
use Livewire\Component;

class CreditDebit extends Component
{
    public $filterCustomer = '';
    public $filterDate = '';
    public $filterInvoice = '';
    public $filterSlip = '';

    public $customerOptions = [];
    public $invoiceOptions = ['DR', 'INVOICE'];
    public $slipOptions = [];

    public $returnItems = []; // ⬅️ Added to hold CustomerReturnItem data

    public function mount()
    {
        $this->customerOptions = Customer::pluck('name')->toArray();
        // Format: RS-001, RS-002, etc.
        $this->slipOptions = CustomerReturn::pluck('id')->map(function ($id) {
            return 'RS-' . str_pad($id, 3, '0', STR_PAD_LEFT);
        })->toArray();

        $this->loadReturnItems(); // Load all by default
    }


    public function loadReturnItems()
    {
        $this->returnItems = CustomerReturnItem::with(['product', 'return'])->get();
    }

    public function render()
    {
        return view('livewire.credit-debit');
    }
}
