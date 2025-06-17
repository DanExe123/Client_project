<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\CustomerReturn;
use App\Models\CustomerReturnItem;
use App\Models\ReleasedItem;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\SaveReturnCredit;
use Livewire\Component;

class CreditDebit extends Component
{
    use WithPagination;
    #[Url(as: 'search', history: true)]
    public $search = '';
    public $perPage = 5;

    public $filterCustomer = '';
    public $filterDate = '';
    public $filterInvoice = '';
    public $filterSlip = '';
    public $order_date;

    public $customerOptions = [];
    public $invoiceOptions = ['DR', 'INVOICE'];
    public $slipOptions = [];

    public $returnItems = [];
    public $savedItems = [];
    public $totalAmount = 0;
    public $releasedTotalAmount = 0;

    public function saveCredit()
    {
        foreach ($this->savedItems as $itemId) {
            $item = CustomerReturnItem::with('return')->find($itemId);
    
            if (!$item || !$item->return) continue;
    
            // Save to save_return_credit table
            SaveReturnCredit::create([
                'return_id'           => $item->return_id,
                'customer_id'         => $item->return->customer_id,
                'order_date'          => $item->return->order_date,
                'product_barcode'     => $item->product_barcode,
                'product_description' => $item->product_description,
                'quantity'            => $item->quantity,
                'unit_price'          => $item->unit_price,
                'subtotal'            => $item->subtotal,
            ]);
    
            // Deduct from released_items
            $releasedItem = ReleasedItem::where('product_barcode', $item->product_barcode)
                ->where('customer_id', $item->return->customer_id)
                ->first();
    
            if ($releasedItem) {
                $deduction = $item->quantity * $item->unit_price;
                $releasedItem->total_amount = max(0, $releasedItem->total_amount - $deduction);
                $releasedItem->save();
            }
    
    
            $item->delete();
        }
    
        // Reset state
        $this->savedItems = [];
        $this->calculateTotalAmount();
        $this->calculateReleasedTotalAmount();
    
        session()->flash('message', 'Selected items saved to credit and deducted from released total.');
        return redirect()->route('credit-debit');   
    }
    
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
        $this->calculateReleasedTotalAmount();
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

    public function removeToSave($itemId)
    {
        $this->savedItems = collect($this->savedItems)
            ->reject(fn($item) => $item == $itemId)
            ->values()
            ->toArray();
    
        $this->calculateTotalAmount();
    }
    
        public function addToSave($id)
        {
            if (!in_array($id, $this->savedItems)) {
                $this->savedItems[] = $id;
                $this->calculateTotalAmount();
            }
        }

        public function calculateTotalAmount()
    {
        $this->totalAmount = CustomerReturnItem::whereIn('id', $this->savedItems)
            ->get()
            ->sum(function ($item) {
                return $item->unit_price * $item->quantity;
            });
    }
    public function getDifferenceAmountProperty()
    {
        return $this->releasedTotalAmount - $this->totalAmount;
    }


    public function calculateReleasedTotalAmount()
    {
        if (!$this->filterCustomer) {
            $this->releasedTotalAmount = 0;
            return;
        }

        $this->releasedTotalAmount = ReleasedItem::where('customer_id', $this->filterCustomer)
            ->sum('total_amount');
    }


    public function render()
    {
        $returnItemsPaginated = CustomerReturnItem::with(['product', 'return'])
            ->whereHas('return', function ($q) {
                if (!empty($this->filterCustomer)) {
                    $q->where('customer_id', $this->filterCustomer);
                }
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('product_barcode', 'like', '%' . $this->search . '%')
                          ->orWhere('product_description', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(5);
    
          $savedRows = CustomerReturnItem::whereIn('id', $this->savedItems)->get();
        return view('livewire.credit-debit', compact('returnItemsPaginated', 'savedRows'));

    }
    
}
