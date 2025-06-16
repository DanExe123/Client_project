<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Supplier;

class Editproduct extends Component
{
    public $productId;
    public $barcode;
    public $supplier_id;
    public $description;
    public $highest_uom;
    public $lowest_uom;
    public $price;
    public $selling_price;
    public $status;

    public function mount($id)
    {
        $this->productId = $id;

        $product = Product::findOrFail($id);

        // Pre-fill the form
        $this->barcode = $product->barcode;
        $this->supplier_id = $product->supplier_id;
        $this->description = $product->description;
        $this->highest_uom = $product->highest_uom;
        $this->lowest_uom = $product->lowest_uom;
        $this->price = $product->price;
        $this->selling_price = $product->selling_price;
        $this->status = $product->status;
        
    }

    public function updateProduct()
    {
        $this->validate([
            'barcode' => 'required|numeric|min:0|unique:products,barcode,' . $this->productId,
            'supplier_id' => 'required|exists:suppliers,id',
            'description' => 'required|string',
            'highest_uom' => 'nullable|string',
            'lowest_uom' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        Product::where('id', $this->productId)->update([
            'barcode' => $this->barcode,
            'supplier_id' => $this->supplier_id,
            'description' => $this->description,
            'highest_uom' => $this->highest_uom,
            'lowest_uom' => $this->lowest_uom,
            'price' => $this->price,
            'selling_price' => $this->selling_price,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Product updated successfully.');

        return redirect()->route('product-master');
    }

    public function render()
    {
        $suppliers = Supplier::all(); // Fetch all suppliers

        return view('livewire.editproduct', [
            'suppliers' => $suppliers, // Pass to view
        ]);
    }
}
