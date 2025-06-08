<?php

namespace App\Livewire\Admin\MasterFiles;

use Livewire\Component;
use App\Models\Customer;

class CustomerMaster extends Component
{
    public $customers = [];

    public function mount()
    {
        $this->customers = Customer::all();
    }

    public function render()
    {
        return view('livewire.admin.masterfiles.customer-master', [
            'customers' => $this->customers,
        ]);
    }
}
