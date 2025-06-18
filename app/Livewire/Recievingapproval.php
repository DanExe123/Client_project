<?php

namespace App\Livewire;

use App\Models\PurchaseOrder;
use App\Models\Receiving;
use App\Models\ReceivingItem;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\PurchaseOrderItem;
use Livewire\Component;
use App\Models\Supplier;
use App\Models\ReceivedItem;

use Carbon\Carbon;


class Recievingapproval extends Component
{
    public $supplier_name;
    public $purchaseOrderId;
    public $items = [];
    public $grandTotal;
    public $receipt_type, $order_date, $po_number, $total_amount, $purchase_discount, $remarks, $status;

    public function mount($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::with(['items.product', 'supplier'])->findOrFail($purchaseOrderId);

        $this->purchaseOrderId = $purchaseOrderId;

        // PO fields
        $this->supplier_name = $purchaseOrder->supplier->name ?? 'N/A';
        $this->receipt_type = $purchaseOrder->receipt_type;
        $this->order_date = Carbon::parse($purchaseOrder->order_date)->format('Y-m-d');
        $this->po_number = $purchaseOrder->po_number;
        $this->total_amount = $purchaseOrder->total_amount;
        $this->purchase_discount = $purchaseOrder->purchase_discount;
        $this->remarks = $purchaseOrder->remarks;
        $this->status = $purchaseOrder->status;

        // Fetch each PO item
        $this->items = $purchaseOrder->items->map(function ($item) {
            return [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'barcode' => $item->product_barcode ?? $item->product->barcode ?? '',
                'description' => $item->product_description ?? $item->product->description ?? '',
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->product_discount,
                'subtotal' => $item->subtotal,
            ];
        })->toArray();
        $this->grandTotal = collect($this->items)->sum('subtotal');
    }

    public function approve()
    {
        try {
            $this->grandTotal = collect($this->items)->sum('subtotal');

            // 1. Create the receiving record
            $receiving = Receiving::create([
                'po_number' => $this->po_number,
                'supplier_id' => PurchaseOrder::find($this->purchaseOrderId)->supplier_id,
                'receipt_type' => $this->receipt_type,
                'order_date' => $this->order_date,
                'purchase_discount' => $this->purchase_discount ?? 0,
                'grand_total' => $this->grandTotal,
                'remarks' => $this->remarks,
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // 2. Loop through items and create ReceivingItem + ReceivedItem entries
            foreach ($this->items as $item) {
                ReceivingItem::create([
                    'receiving_id' => $receiving->id,
                    'barcode' => $item['barcode'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'subtotal' => $item['subtotal'],
                ]);

                // 👉 Also create flattened ReceivedItem record
                ReceivedItem::create([
                    'receiving_id' => $receiving->id,
                    'po_number' => $this->po_number,
                    'supplier_id' => $receiving->supplier_id,
                    'receipt_type' => $this->receipt_type,
                    'order_date' => $this->order_date,
                    'purchase_discount' => $this->purchase_discount ?? 0,
                    'grand_total' => $this->grandTotal,
                    'remarks' => $this->remarks,
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),

                    'barcode' => $item['barcode'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $item['discount'] ?? 0,
                    'subtotal' => $item['subtotal'],
                ]);

                // 3. Update product stock
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->quantity += $item['quantity']; // received = add to stock
                    $product->save();
                }
            }

            // 4. Update PO status
            $po = PurchaseOrder::find($this->purchaseOrderId);
            if ($po) {
                $po->status = 'Approved';
                $po->save();
            }

            session()->flash('success', 'Receiving successfully approved and saved.');
            return redirect()->route('recieving'); // Adjust this route if needed

        } catch (\Exception $e) {
            Log::error('Receiving Approval Error: ' . $e->getMessage());
            session()->flash('error', 'Something went wrong while approving.');
        }
    }
    public function updateSubtotal($index)
    {
        $qty = (float) $this->items[$index]['quantity'] ?? 0;
        $unitPrice = (float) $this->items[$index]['unit_price'] ?? 0;
        $discount = (float) $this->items[$index]['discount'] ?? 0;

        $subtotal = ($qty * $unitPrice) * (1 - $discount / 100);
        $this->items[$index]['subtotal'] = $subtotal;

        $this->grandTotal = collect($this->items)->sum('subtotal');
    }


    public function render()
    {

        return view('livewire.recievingapproval', [
        ]);
    }
}
