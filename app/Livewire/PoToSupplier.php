<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\Product;

class PoToSupplier extends Component
{

    public function render()
    {
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('livewire.po-to-supplier', [
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }
}
