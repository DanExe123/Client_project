<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;


class ReturnByCustomer extends Component
{
    public function render()
    {
        $customers = Customer::all();
        $products = Product::select('id', 'description', 'price')->get();
        return view('livewire.return-by-customer', [
            'customers' => $customers,
            'products' => $products,
        ]);
    }
}
