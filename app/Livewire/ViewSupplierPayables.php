<?php

namespace App\Livewire;

use App\Models\ReceivedItem;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\Payment;
use App\Models\SupplierReturn;

class ViewSupplierPayables extends Component
{
    public $supplier;

    public function mount(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }

    public function render()
    {
        $receiveditem = ReceivedItem::where('supplier_id', $this->supplier->id)
            ->orderBy('created_at')
            ->get();

        $payments = Payment::where('supplier_id', $this->supplier->id)
            ->orderBy('created_at')
            ->get();

        $returns = SupplierReturn::where('supplier_id', $this->supplier->id)
            ->where('status', 'approved')
            ->orderBy('approved_at')
            ->get();

        return view('livewire.view-supplier-payables', compact('receiveditem', 'payments', 'returns'))
            ->with('supplier', $this->supplier);
    }
}