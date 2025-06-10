<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class Editproduct extends Component
{
    public $productId;
    public $barcode;
    public $supplier;
    public $description;
    public $highest_uom;
    public $lowest_uom;
    public $price;
    public $selling_price;

    public function mount($id)
    {
        $this->productId = $id;

        $product = Product::findOrFail($id);

        // Pre-fill the form
        $this->barcode = $product->barcode;
        $this->supplier = $product->supplier;
        $this->description = $product->description;
        $this->highest_uom = $product->highest_uom;
        $this->lowest_uom = $product->lowest_uom;
        $this->price = $product->price;
        $this->selling_price = $product->selling_price;
        
    }

    public function updateProduct()
    {
        $this->validate([
            'barcode' => 'required|string|unique:products,barcode,' . $this->productId,
            'supplier' => 'required|string',
            'description' => 'required|string',
            'highest_uom' => 'nullable|string',
            'lowest_uom' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
        ]);

        Product::where('id', $this->productId)->update([
            'barcode' => $this->barcode,
            'supplier' => $this->supplier,
            'description' => $this->description,
            'highest_uom' => $this->highest_uom,
            'lowest_uom' => $this->lowest_uom,
            'price' => $this->price,
            'selling_price' => $this->selling_price,
        ]);

        session()->flash('message', 'Product updated successfully.');

        return redirect()->route('product-master');
    }

    public function render()
    {
        return view('livewire.editproduct');
    }
}
