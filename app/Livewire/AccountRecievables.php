<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\ReleasedItem;
use App\Models\SalesRelease;
use Livewire\WithPagination;

class AccountRecievables extends Component
{
    use WithPagination;
    
    public $customer;

    public $perPage = 5;

  
    public function render()
    {
        $customers = Customer::with(['salesReleases.releasedItems'])->paginate($this->perPage);

        // Optional: Add transaction formatting here if needed

        return view('livewire.account-recievables', compact('customers'));
    }
}
