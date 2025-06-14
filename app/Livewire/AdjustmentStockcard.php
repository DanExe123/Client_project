<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

class AdjustmentStockcard extends Component
{
    // The single product being adjusted
    public Product $product;

    // Holds each field by product ID
    public array $productsData = [];

    protected $rules = [
        'productsData.*.quantity' => 'required|integer|min:1',
        'productsData.*.quantity_lowest' => 'required|integer|min:1',

    ];

    /**
     * @param  \App\Models\Product  $product
     */
    public function mount(Product $product)
    {
        $this->product = $product;

        // Initialize the editable data for this product
        $this->productsData[$product->id] = [
            'barcode' => $product->barcode,
            'productName' => $product->description,
            'highestUom' => $product->highest_uom,
            'lowest_uom' => $product->lowest_uom,
            'damages' => $product->damages,
            'quantity' => $product->quantity,
            'quantity_lowest' => $product->quantity_lowest,
        ];
    }

    public function submitQuantity()
    {
        // Validate quantity only
        $this->validateOnly("productsData.{$this->product->id}.quantity");
        $this->validateOnly("productsData.{$this->product->id}.quantity_lowest");

        // Grab the new quantity
        $quantity = $this->productsData[$this->product->id]['quantity'];
        $quantity_lowest = $this->productsData[$this->product->id]['quantity_lowest'];

        // Persist: you could update the product or create an adjustment record
        $this->product->update([
            'quantity' => $quantity,
            'quantity_lowest' => $quantity_lowest,
        ]);

        session()->flash('success', 'Quantity updated!');
        return redirect()->route('stockcard');
    }


    public function render()
    {
        return view('livewire.adjustment-stockcard', [
            'product' => $this->product,
        ]);
    }
}
