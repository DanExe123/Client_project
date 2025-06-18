<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SalesRelease;

class SalesBook extends Component
{
    public $startDate;
    public $endDate;
    public $showTable = false;

    public function updatedStartDate()
    {
        $this->checkDates();
    }

    public function updatedEndDate()
    {
        $this->checkDates();
    }

    public function checkDates()
    {
        if ($this->startDate && $this->endDate) {
            $this->showTable = true;
        } else {
            $this->showTable = false;
        }
    }

    public function getSalesProperty()
    {
        if (!$this->showTable)
            return collect();

        return SalesRelease::with(['customer', 'paymentInvoice'])
            ->where('receipt_type', 'Invoice')
            ->whereDate('release_date', '>=', $this->startDate)
            ->whereDate('release_date', '<=', $this->endDate)
            ->orderBy('release_date', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.sales-book', [
            'sales' => $this->sales,
        ]);
    }
}
