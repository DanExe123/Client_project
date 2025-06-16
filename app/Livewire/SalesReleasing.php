<?php

namespace App\Livewire;

use App\Models\CustomerPurchaseOrder;
use App\Models\SalesRelease;
use App\Models\SalesReleaseItem;
use App\Models\Product;
use App\Models\ReleasedItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Http\Request;


class SalesReleasing extends Component
{
    public $invoiceOrders = [];
    public $drOrders = [];
    public $releasedOrders = [];
    public $po;

    public function mount()
    {
        $this->invoiceOrders = CustomerPurchaseOrder::with('customer')
            ->where('receipt_type', 'INVOICE')
            ->where('status', 'pending') // only pending
            ->get();

        $this->drOrders = CustomerPurchaseOrder::with('customer')
            ->where('receipt_type', 'DR')
            ->where('status', 'pending') // only pending
            ->get();
        // NEW: fetch all served sales releases
        $this->releasedOrders = SalesRelease::with(['customer', 'items'])
            ->orderByDesc('release_date')
            ->get();
    }

    public function render()
    {
        return view('livewire.sales-releasing', [
            'invoiceOrders' => $this->invoiceOrders,
            'drOrders' => $this->drOrders,
            'releasedOrders' => $this->releasedOrders,
        ]);
    }

    public function reprintInvoice($id)
    {
        \Log::info("Reprint Invoice clicked for PO ID: $id");
    }

    public function serve(Request $request, $id)
    {
        $po = CustomerPurchaseOrder::with(['items', 'customer'])->findOrFail($id);
        $products = json_decode($request->input('products'), true);

        $subtotal = 0;
        $validatedItems = [];

        foreach ($products as $item) {
            $orderedQty = $item['quantity'];
            $poItem = $po->items->firstWhere('product_id', $item['product_id']);
            $product = Product::find($item['product_id']);

            if (!$poItem) {
                return back()->withErrors([
                    'quantity' => "Product {$item['product_description']} is not in the purchase order."
                ]);
            }

            if ($orderedQty > $poItem->quantity) {
                return back()->withErrors([
                    'quantity' => "Quantity for {$item['product_description']} exceeds purchase order quantity."
                ]);
            }

            if (!$product || $orderedQty > $product->quantity) {
                return back()->withErrors([
                    'quantity' => "Not enough stock for {$item['product_description']}. Available: {$product->quantity}, Requested: {$orderedQty}."
                ]);
            }

            $unitPrice = floatval($item['price']);
            $discountRate = floatval($item['discount']);
            $lineSubtotal = $unitPrice * $orderedQty;
            $lineDiscount = $lineSubtotal * ($discountRate / 100);
            $lineTotal = $lineSubtotal - $lineDiscount;

            $subtotal += $lineTotal;

            $validatedItems[] = [
                'product_id' => $item['product_id'],
                'product_description' => $item['product_description'],
                'product_barcode' => $item['product_barcode'],
                'quantity' => $orderedQty,
                'unit_price' => $unitPrice,
                'discount' => $discountRate,
                'subtotal' => round($lineTotal, 2)
            ];
        }

        $poDiscountRate = floatval($po->purchase_discount ?? 0);
        $discountAmount = $subtotal * ($poDiscountRate / 100);
        $totalAfterDiscount = $subtotal - $discountAmount;
        $vatPercent = 12;
        $amountNetOfVat = $totalAfterDiscount / 1.12;
        $addVat = $amountNetOfVat * 0.12;
        $grandTotal = $totalAfterDiscount;

        $release = SalesRelease::create([
            'purchase_order_id' => $po->id,
            'receipt_type' => $po->receipt_type,
            'customer_id' => $po->customer_id,
            'release_date' => now(),
            'remarks' => $po->remarks,
            'discount' => $poDiscountRate,
            'discount_amount' => round($discountAmount, 2),
            'total_amount' => round($subtotal, 2),
            'vat_percent' => $vatPercent,
            'amount_net_of_vat' => round($amountNetOfVat, 2),
            'add_vat' => round($addVat, 2),
            'total_with_vat' => round($grandTotal, 2),
            'created_by' => Auth::id(),
        ]);

        foreach ($validatedItems as $item) {
            SalesReleaseItem::create([
                'sales_release_id' => $release->id,
                'product_id' => $item['product_id'],
                'product_description' => $item['product_description'],
                'product_barcode' => $item['product_barcode'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount' => $item['discount'],
                'subtotal' => $item['subtotal'],
            ]);

            // 👉 Create ReleasedItem entry
            ReleasedItem::create([
                'sales_release_id' => $release->id,
                'purchase_order_id' => $po->id,
                'receipt_type' => $po->receipt_type,
                'customer_id' => $po->customer_id,
                'release_date' => now(),
                'discount' => $poDiscountRate,
                'remarks' => $po->remarks,
                'created_by' => Auth::id(),
                'vat_percent' => $vatPercent,
                'total_amount' => round($subtotal, 2),
                'amount_net_of_vat' => round($amountNetOfVat, 2),
                'total_with_vat' => round($grandTotal, 2),
                'printed_at' => null,
                'add_vat' => true,

                'product_id' => $item['product_id'],
                'product_description' => $item['product_description'],
                'product_barcode' => $item['product_barcode'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'discount_item' => $item['discount'],
                'subtotal' => $item['subtotal'],
            ]);

            // Deduct stock
            $product = Product::find($item['product_id']);
            if ($product) {
                $product->quantity -= $item['quantity'];
                $product->save();
            }
        }

        $po->status = 'served';
        $po->save();

        return redirect()->route('serve-print-preview', $release->id);
    }
    public function printPreview($id)
    {
        $release = SalesRelease::with(['customer', 'items'])->findOrFail($id);

        // Mark as printed if not yet
        if (!$release->printed_at) {
            $release->printed_at = now();
            $release->save();
        }

        return view('livewire.print-preview', compact('release'));
    }
}
