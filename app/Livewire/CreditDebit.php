<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\CustomerReturn;
use App\Models\CustomerReturnItem;
use Livewire\Attributes\Url;
use Livewire\Component;

class CreditDebit extends Component
{
    #[Url(as: 'search', history: true)]
    public $search = '';

    public $filterCustomer = '';
    public $filterDate = '';
    public $filterInvoice = '';
    public $filterSlip = '';

    public $customerOptions = [];
    public $invoiceOptions = ['DR', 'INVOICE'];
    public $slipOptions = [];

    public $returnItems = [];

    public function mount()
    {
        $this->customerOptions = Customer::pluck('name', 'id')->toArray();

        $this->slipOptions = CustomerReturn::pluck('id')->map(function ($id) {
            return 'RS-' . str_pad($id, 3, '0', STR_PAD_LEFT);
        })->toArray();

        $this->loadReturnItems();
    }

    public function updatedSearch()
    {
        $this->loadReturnItems();
    }

    public function updatedFilterCustomer()
    {
        $this->loadReturnItems();
    }
    

    public function loadReturnItems()
    {
        // Don't load anything if no customer selected
        if (empty($this->filterCustomer)) {
            $this->returnItems = [];
            return;
        }
    
        $query = CustomerReturnItem::with(['product', 'return'])
            ->whereHas('return', function ($q) {
                $q->where('customer_id', $this->filterCustomer);
            });
    
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('product_barcode', 'like', '%' . $this->search . '%')
                  ->orWhere('product_description', 'like', '%' . $this->search . '%');
            });
        }
    
        $this->returnItems = $query->get();
    }
    

    public function render()
    {
        return view('livewire.credit-debit');
    }
}
