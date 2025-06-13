<?php

namespace App\Livewire;

use App\Models\PurchaseOrder;
use App\Models\ReceivingStatus;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Recievingapproval extends Component
{
    public $purchaseOrderId;
    public $items = [];

    public function mount($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($purchaseOrderId);

        $this->purchaseOrderId = $purchaseOrderId;

        $this->items = $purchaseOrder->items->map(function ($item) {
            return [
                'id' => $item->id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->discount,
                'subtotal' => $item->subtotal,
            ];
        })->toArray();
    }

    public function approve()
    {
        foreach ($this->items as $item) {
            ReceivingStatus::create([
                'purchase_order_items_id' => $item['id'],
                'status' => 'Approved',
            ]);
        }
    }
        
    
    

    public function render()
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($this->purchaseOrderId);

        return view('livewire.recievingapproval', [
            'items' => $purchaseOrder->items,
        ]);
    }
}
