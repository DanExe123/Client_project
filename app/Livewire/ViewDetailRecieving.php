<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PurchaseOrder;

class ViewDetailRecieving extends Component
{
    public $purchaseOrderId;

    public function mount($id)
    {
        $this->purchaseOrderId = $id;
    }

    public function render()
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($this->purchaseOrderId);

        return view('livewire.view-detail-recieving', [
            'purchaseOrder' => $purchaseOrder,
        ]);
    }
}
