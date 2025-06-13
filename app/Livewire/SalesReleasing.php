<?php

namespace App\Livewire;

use App\Models\CustomerPurchaseOrder;
use App\Models\SalesRelease;
use App\Models\SalesReleaseItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SalesReleasing extends Component
{
    public $invoiceOrders = [];
    public $drOrders = [];
    public $allOrders = [];
    public $po;

    public function mount()
    {
        $this->invoiceOrders = CustomerPurchaseOrder::with('customer')
            ->where('receipt_type', 'INVOICE')
            ->get();

        $this->drOrders = CustomerPurchaseOrder::with('customer')
            ->where('receipt_type', 'DR')
            ->get();

        $this->allOrders = CustomerPurchaseOrder::with('customer')
            ->whereIn('receipt_type', ['INVOICE', 'DR'])
            ->get();
    }

    public function render()
    {
        return view('livewire.sales-releasing', [
            'invoiceOrders' => $this->invoiceOrders,
            'drOrders' => $this->drOrders,
            'allOrders' => $this->allOrders,
        ]);
    }

    public function reprintInvoice($id)
    {
        \Log::info("Reprint Invoice clicked for PO ID: $id");
    }

    public function serve($id)
    {
        $po = CustomerPurchaseOrder::with(['items', 'customer'])->findOrFail($id);

        // Create the sales release
        $release = SalesRelease::create([
            'purchase_order_id' => $po->id,
            'receipt_type' => $po->receipt_type,
            'customer_id' => $po->customer_id,
            'release_date' => now(),
            'discount' => $po->discount,
            'remarks' => $po->remarks,
            'created_by' => Auth::id(),
        ]);

        // Save each item into sales_release_items
        foreach ($po->items as $item) {
            SalesReleaseItem::create([
                'sales_release_id' => $release->id,
                'product_id' => $item->product_id,
                'product_description' => $item->product_description,
                'product_barcode' => $item->product_barcode,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'discount' => $item->product_discount,
                'subtotal' => $item->subtotal,
            ]);
        }

        // Optional: mark the PO as served
        $po->status = 'served';
        $po->save();

        // Redirect to print preview (this is Livewire-specific)
        return redirect()->route('serve-print-preview', $release->id);
    }
    public function printPreview($id)
    {
        $release = SalesRelease::with(['customer', 'items'])->findOrFail($id);
        return view('livewire.print-preview', compact('release'));

    }
}
