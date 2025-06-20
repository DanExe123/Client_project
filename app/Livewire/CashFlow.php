<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PaymentInvoice;
use App\Models\CustomerPayment;
use App\Models\Payment;
use App\Models\ExpenseTable;
use Illuminate\Support\Facades\DB;

class CashFlow extends Component
{
    public $cashFlowEntries = [];

    public function mount()
    {
        // Get all dates in 'Y-m-d' format as strings to avoid Carbon instance mismatch
        $datesFromInvoices = PaymentInvoice::selectRaw('DATE(created_at) as date')
            ->pluck('date')
            ->map(fn($d) => date('Y-m-d', strtotime($d)));

        $datesFromPayments = Payment::selectRaw('DATE(created_at) as date')
            ->pluck('date')
            ->map(fn($d) => date('Y-m-d', strtotime($d)));

        $datesFromExpenses = ExpenseTable::selectRaw('DATE(created_at) as date')
            ->pluck('date')
            ->map(fn($d) => date('Y-m-d', strtotime($d)));

        // Merge all date collections, deduplicate as strings, then sort
        $allDates = $datesFromInvoices
            ->merge($datesFromPayments)
            ->merge($datesFromExpenses)
            ->unique()
            ->sort();

        $runningBalance = 0;

        foreach ($allDates as $date) {
            $customerPayments = PaymentInvoice::whereDate('created_at', $date)->sum('amount');
            $paymentToSupplier = Payment::whereDate('created_at', $date)->sum('amount_paid');
            $expenses = ExpenseTable::whereDate('created_at', $date)->sum('amount');

            $beginningBalance = $runningBalance;
            $endingBalance = $beginningBalance + $customerPayments - $paymentToSupplier - $expenses;

            $this->cashFlowEntries[] = [
                'date' => $date,
                'beginning_balance' => $beginningBalance,
                'customer_payments' => $customerPayments,
                'payment_to_supplier' => $paymentToSupplier,
                'expenses' => $expenses,
                'ending_balance' => $endingBalance,
            ];

            $runningBalance = $endingBalance;
        }
    }
    public function render()
    {
        return view('livewire.cash-flow', [
            'cashFlowEntries' => $this->cashFlowEntries,
        ]);
    }
}
