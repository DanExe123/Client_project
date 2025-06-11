<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\Product;
class ReturnToSupplier extends Component
{
    public function render()
    {
        $suppliers = Supplier::all();
        $products = Product::select('id', 'description', 'price')->get();


        return view('livewire.return-to-supplier', [
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }
}
