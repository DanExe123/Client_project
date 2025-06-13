<?php

namespace App\Livewire;

use App\Models\PurchaseOrder;
use Livewire\Component;

class SalesReleasing extends Component
{
    public $invoiceOrders = [];
    public $drOrders = [];

    
    public function mount()
    {
        $this->invoiceOrders = PurchaseOrder::with('supplier')
            ->where('receipt_type', 'INVOICE')
            ->get();

        $this->drOrders = PurchaseOrder::with('supplier')
            ->where('receipt_type', 'DR')
            ->get();
    }

    public function render()
    {
        return view('livewire.sales-releasing', [
            'invoiceOrders' => $this->invoiceOrders,
            'drOrders' => $this->drOrders,
        ]);
    }

    public function serve($id)
    {
        \Log::info("Serve clicked for PO ID: $id");
    }

    public function reprintInvoice($id)
    {
        \Log::info("Reprint Invoice clicked for PO ID: $id");
    }
}