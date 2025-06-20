<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SalesRelease;
use Illuminate\Support\Facades\DB;
use App\Models\SaveReturnCredit;
use App\Models\ReleasedItem;

class SalesSummary extends Component
{
    public $sales = [];
    public $monthlySales = [];
    public $totalQuantity = 0;
    public $totalGross = 0;
    public $totalNet = 0;
    public $totalDiscount = 0;
    public $totalReturns = 0;

    public function mount()
    {
        $query = SalesRelease::with(['customer', 'items.product', 'paymentInvoice'])->get();

        $this->sales = $query->flatMap(function ($sale) {
            return $sale->items->map(function ($item) use ($sale) {
                $gross = $item->quantity * $item->unit_price;
                $discount = $sale->discount ?? 0;

                $releasedItem = ReleasedItem::where('sales_release_id', $sale->id)
                    ->where('product_id', $item->product_id)
                    ->first();

                $returnAmount = 0;
                if ($releasedItem) {
                    $returnAmount = SaveReturnCredit::where('released_item_id', $releasedItem->id)
                        ->sum('applied_amount');
                }

                $net = $gross - $discount - $returnAmount;

                // Accumulate totals
                $this->totalQuantity += $item->quantity;
                $this->totalGross += $gross;
                $this->totalDiscount += $discount;
                $this->totalReturns += $returnAmount;
                $this->totalNet += $net;

                return [
                    'date' => $sale->created_at->format('Y-m-d'),
                    'invoice_number' => $sale->id,
                    'customer_name' => $sale->customer->name ?? 'N/A',
                    'product_name' => $item->product->description ?? 'N/A',
                    'quantity_sold' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'gross_sales' => $gross,
                    'discount' => $discount,
                    'returns' => $returnAmount,
                    'net_sales' => $net,
                    'payment_status' => $sale->paymentInvoice && $sale->paymentInvoice->payment_method ? 'Paid' : 'Unpaid',
                    'payment_type' => $sale->paymentInvoice->payment_method ?? '-',
                ];
            });
        })->toArray();

        // Monthly summary
        $this->monthlySales = collect($this->sales)
            ->groupBy(function ($sale) {
                return \Carbon\Carbon::parse($sale['date'])->format('Y-m');
            })
            ->map(function ($group, $month) {
                return [
                    'month' => $month,
                    'quantity_sold' => $group->sum('quantity_sold'),
                    'gross_sales' => $group->sum('gross_sales'),
                    'discount' => $group->sum('discount'),
                    'returns' => $group->sum('returns'),
                    'net_sales' => $group->sum('net_sales'),
                ];
            })->values()->all();
    }

    public function render()
    {
        return view('livewire.sales-summary', [
            'totalQuantity' => $this->totalQuantity,
            'totalGross' => $this->totalGross,
            'totalDiscount' => $this->totalDiscount,
            'totalReturns' => $this->totalReturns,
            'totalNet' => $this->totalNet,
            'monthlySales' => $this->monthlySales,
        ]);
    }
}
