<?php

namespace App\Livewire;

use App\Models\PurchaseOrder;
use App\Models\Recievings;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
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
                'description' => $item->product_description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->product_discount,
                'subtotal' => $item->subtotal,
            ];
        })->toArray();
    }

    public function approve()
    {
        foreach ($this->items as $item) {
            // Create Receiving Record
            Recievings::create([
                'purchase_order_item_id' => $item['id'],
                'status' => 'Approved',
            ]);
    
            // Get the PurchaseOrderItem model
            $poItem = PurchaseOrderItem::find($item['id']);
    
            if ($poItem) {
                // Reduce product stock
                $product = $poItem->product;
    
                if ($product) {
                    $product->quantity -= $poItem->quantity; // Subtract ordered quantity
                    $product->save();
                }
            }
        }
    
        // Update purchase order status
        $po = PurchaseOrder::find($this->purchaseOrderId);
        $po->status = 'Approved';
        $po->save();
    
        session()->flash('success', 'Items approved and recorded in recievings.');
    
        return redirect()->route('recieving');
    }
    

    public function render()
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($this->purchaseOrderId);

        return view('livewire.recievingapproval', [
            'items' => $purchaseOrder->items,
        ]);
    }
}
