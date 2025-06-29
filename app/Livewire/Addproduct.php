<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Supplier;

class AddProduct extends Component
{
    public $barcode;
    public $supplier_id;
    public $description;
    public $highest_uom;
    public $lowest_uom;
    public $price;
    public $selling_price;
    public $lowest_uom_quantity;


    protected $rules = [
        'barcode' => 'required|numeric|min:0|unique:products,barcode',
        'supplier_id' => 'required|exists:suppliers,id',
        'description' => 'required|string',
        'highest_uom' => 'nullable|string',
        'lowest_uom' => 'nullable|string',
        'lowest_uom_quantity' => 'nullable|numeric|min:0',
        'price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
    ];

    public function submit()
    {
        $this->validate();

        Product::create([
            'barcode' => $this->barcode,
            'supplier_id' => $this->supplier_id,
            'description' => $this->description,
            'highest_uom' => $this->highest_uom,
            'lowest_uom' => $this->lowest_uom,
            'lowest_uom_quantity' => $this->lowest_uom_quantity ?? 0,
            'price' => $this->price,
            'selling_price' => $this->selling_price,
        ]);

        session()->flash('message', 'Successfully added new product.');

        $this->reset();
        return redirect()->route('product-master');
    }
    public function render()
    {
        $suppliers = Supplier::select('id', 'name')->get();

        return view(
            'livewire.addproduct',
            [
                'suppliers' => $suppliers,
            ]
        );
    }
}
