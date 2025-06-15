<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CustomerPurchaseOrderItem;

class UnserveredLacking extends Component
{
    public $unservedData = [];

    public function mount()
    {
        $items = CustomerPurchaseOrderItem::with([
            'purchaseOrder.customer',
            'purchaseOrder.salesReleases.items'
        ])->get();

        $this->unservedData = $items->map(function ($item) {
            // ❌ Skip if there are no sales releases yet
            if ($item->purchaseOrder->salesReleases->isEmpty()) {
                return null;
            }

            $servedQty = $item->purchaseOrder->salesReleases
                ->flatMap(fn ($release) => $release->items)
                ->where('product_id', $item->product_id)
                ->sum('quantity');

            return [
                'date' => optional($item->purchaseOrder->order_date)->format('Y-m-d'),
                'customer_name' => $item->purchaseOrder->customer->name ?? 'N/A',
                'product_description' => $item->product_description,
                'po_quantity' => $item->quantity,
                'served_quantity' => $servedQty,
                'difference' => $item->quantity - $servedQty,
            ];
        })
        ->filter(fn ($row) => $row !== null && $row['difference'] > 0) // skip null and fully served
        ->values();
    }

    


    public function render()
    {
        return view('livewire.unservered-lacking', [
            'unservedData' => $this->unservedData,
        ]);
    }
}


