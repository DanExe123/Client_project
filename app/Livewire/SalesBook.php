<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SalesRelease;

class SalesBook extends Component
{
    public $startDate;
    public $endDate;

    public function render()
    {
        $query = SalesRelease::with('customer')
            ->where('receipt_type', 'Invoice');

        if ($this->startDate) {
            $query->whereDate('release_date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('release_date', '<=', $this->endDate);
        }

        $sales = $query->orderBy('release_date', 'desc')->get();

        return view('livewire.sales-book', [
            'sales' => $sales,
        ]);
    }
}
