<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\Product;

class PoToSupplier extends Component
{
    public $selectedSupplierId;
    public $receiptType;
    public $poDate;
    public function render()
    {
        $suppliers = Supplier::all();
        $products = Product::select('id', 'description', 'price')->get();
        return view('livewire.po-to-supplier', [
            'suppliers' => $suppliers,
            'products' => $products,
        ]);
    }
}
