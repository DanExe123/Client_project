<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CustomerPurchaseOrder;

class ServeSaleReleasing extends Component
{
    public $po;

    public function mount($id)
    {
        $this->po = CustomerPurchaseOrder::with(['customer', 'items.product'])->findOrFail($id);
    }
    public function render()
    {
        return view('livewire.serve-sale-releasing'); // ← path to Blade view
    }
}