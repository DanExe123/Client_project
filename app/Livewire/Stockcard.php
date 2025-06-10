<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class Stockcard extends Component
{
    public $products;
    public $selectedProductId = [];

    public $barcode;
    public $productName;
    public $highestUom;
    public $pc;
    public $damages;
    public $quantity;

    public function mount()
    {
        $this->products = Product::all();
    }

  

    public function render()
    {
        $this->products = Product::all(); // Ensure products are always up-to-date
        return view('livewire.stockcard', [
            'products' => $this->products,
        ]);
    }
}
