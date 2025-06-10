<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier; // Ensure you have a Supplier model


class SupplierMaster extends Component
{
    use WithPagination;
    public $selected = [];
    public function toggleAll()
    {
        $this->selected = Supplier::paginate(5)->pluck('id')->toArray();
    }
    public function render()
    {
        $suppliers = Supplier::paginate(5);
        return view('livewire.supplier-master', compact('suppliers'));
    }
}

