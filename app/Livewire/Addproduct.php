<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Supplier;

class AddProduct extends Component
{
    public $barcode;
    public $supplier;
    public $description;
    public $highest_uom;
    public $lowest_uom;
    public $price;
    public $selling_price;


    protected $rules = [
        'barcode' => 'required|numeric|min:0|unique:products,barcode',
        'supplier' => 'required|string',
        'description' => 'required|string',
        'highest_uom' => 'nullable|string',
        'lowest_uom' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'selling_price' => 'required|numeric|min:0',
    ];

    public function submit()
    {
        $this->validate();

        Product::create([
            'barcode' => $this->barcode,
            'supplier' => $this->supplier,
            'description' => $this->description,
            'highest_uom' => $this->highest_uom,
            'lowest_uom' => $this->lowest_uom,
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
