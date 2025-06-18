<?php

namespace App\Livewire;
use App\Models\Supplier;
use App\Models\Receiving;
use App\Models\Payment;
use App\Models\SupplierReturn;
use Livewire\Component;

class PayableLedger extends Component
{
    public $transactions = [];
    
    public function mount()
    {
        $this->transactions = collect()
            ->merge(
                SupplierReturn::with('supplier')->get()->map(function ($return) {
                    return [
                        'supplier_name'     => $return->supplier->name ?? 'Unknown',
                        'transaction_date'  => $return->order_date ?? $return->created_at,
                        'reference_type'    => 'Supplier Return',
                        'reference_number'  => 'SRT-' . str_pad($return->id, 5, '0', STR_PAD_LEFT),
                        'credit'            => -$return->total_amount ?? 0,
                        'balance'           => -$return->total_amount ?? 0,
                        'remarks'           => $return->remarks ?? '-',
                    ];
                })
            )
            ->merge(
                Payment::with('supplier')->get()->map(function ($payment) {
                    return [
                        'supplier_name'     => $payment->supplier->name ?? 'Unknown',
                        'transaction_date'  => $payment->transaction_date ?? $payment->date ?? $payment->created_at,
                        'reference_type'    => 'Payment',
                        'reference_number'  => 'PAY-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT),
                        'credit'            => -$payment->amount_paid ?? 0,
                        'balance'           => -$payment->amount_paid ?? 0,
                        'remarks'           => $payment->remarks ?? '-',
                    ];
                })
            )
            ->sortByDesc('transaction_date')
            ->values(); // Reset index
    }
    public function render()
    {
        return view('livewire.payable-ledger', [
            'suppliers' => Supplier::all(),
        ]);
    }
    
}
