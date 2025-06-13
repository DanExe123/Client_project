<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PurchaseOrder;

class ReprintInvoice extends Component
{
    public $printInvoiceData;

    public function mount($id)
    {
        // Fetch invoice data by ID
        $this->printInvoiceData = PurchaseOrder::with(['supplier', 'items'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.reprint-invoice');
    }
}
