<?php

namespace App\Livewire;

use App\Models\Supplier;
use App\Models\Receiving;
use App\Models\ReceivedItem;

use Livewire\Component;

class PayableLedger extends Component
{
    public $suppliers;

    public function mount()
    {
        $this->suppliers = Supplier::withSum('receiveditem', 'grand_total')->get();
    }

    public function render()
    {
        return view('livewire.payable-ledger', [
            'suppliers' => $this->suppliers,
        ]);
    }
}