<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SalesRelease;
use Carbon\Carbon;

class SalesSummary extends Component
{
    public $startDate;
    public $endDate;
    public $sales = [];

    public function mount()
    {
        // Default date range — this month
        $this->startDate = Carbon::now()->startOfMonth()->toDateString();
        $this->endDate = Carbon::now()->endOfMonth()->toDateString();

        $query = SalesRelease::with(['customer', 'items.product', 'paymentInvoice'])
            ->whereBetween('release_date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->get();

        $this->sales = $query->flatMap(function ($sale) {
            return $sale->items->map(function ($item) use ($sale) {
                $gross = $item->quantity * $item->unit_price;
                $discount = $sale->discount ?? 0;
                return [
                    'date' => \Carbon\Carbon::parse($sale->release_date)->format('Y-m-d'),
                    'invoice_number' => $sale->id,
                    'customer_name' => $sale->customer->name ?? 'N/A',
                    'product_name' => $item->product->description ?? 'N/A',
                    'quantity_sold' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'gross_sales' => $gross,
                    'discount' => $discount,
                    'returns' => 0,
                    'net_sales' => $gross - $discount,
                    'payment_status' => $sale->paymentInvoice && $sale->paymentInvoice->payment_method ? 'Paid' : 'Unpaid',
                    'payment_type' => $sale->paymentInvoice->payment_method ?? '-',
                ];
            });
        })->toArray();
    }

    public function render()
    {
        return view('livewire.sales-summary');
    }
}
