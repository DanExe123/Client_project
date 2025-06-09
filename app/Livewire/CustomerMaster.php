<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CustomerTable;

class CustomerMaster extends Component
{
    use WithPagination;

    public $selected = [];

    public function toggleAll()
    {
        // Select all customer IDs on the current page only (recommended for pagination)
        $this->selected = CustomerTable::paginate(5)->pluck('id')->toArray();
    }

  public function render()
    {
        $customers = CustomerTable::paginate(5);
        return view('livewire.customer-master', compact('customers'));
    }
}

