<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product; // Make sure you have a Product model

class ProductMaster extends Component
{
    use WithPagination;

    public $selected = [];

    public function toggleAll()
    {
        $this->selected = Product::paginate(5)->pluck('id')->toArray();
    }
    public function render()
    {
        $products = Product::paginate(5);
        return view('livewire.product-master', compact('products'));
    }
}