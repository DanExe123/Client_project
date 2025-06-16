<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Receiving;

class ViewReceivingDetails extends Component
{
    public $receiving;

    public function mount($id)
    {
        $this->receiving = Receiving::with(['receivingItems.product', 'supplier'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.view-receiving-details');
    }
}