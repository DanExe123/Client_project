<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CashFlow;
use Illuminate\Support\Facades\DB;

class ViewMonthlyCashFlow extends Component
{
    public $monthlySummary = [];

    public function mount()
    {
        // Group cash flows by month
        $this->monthlySummary = CashFlow::selectRaw('
                DATE_FORMAT(date, "%Y-%m") as month,
                SUM(beginning_balance) as beginning_balance,
                SUM(customer_payments) as customer_payments,
                SUM(payment_to_supplier) as payment_to_supplier,
                SUM(expenses) as expenses,
                SUM(ending_balance) as ending_balance
            ')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.view-monthly-cash-flow');
    }
}
