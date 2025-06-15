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
    public $lowest_uom;
    public $damages;
    public $quantity;
    public $quantity_lowest;
    public $productsData = [];
    public bool $showAdjustmentsModal = false;

    public function selectedProduct($id)
    {
        if (in_array($id, $this->selectedProductId)) {
            // Remove if already selected (uncheck)
            $this->selectedProductId = array_filter(
                $this->selectedProductId,
                fn($item) => $item !== $id
            );
        } else {
            // Add if not selected (check)
            $this->selectedProductId[] = $id;
        }
    }

    public function goToAdjustment()
    {
        // ensure exactly one is selected
        if (count($this->selectedProductId) === 1) {
            // redirect to the route, passing the single selected ID
            return redirect()->route('adjustment-stockcard', [
                'product' => $this->selectedProductId[0],
            ]);
        }
    }
    public function toggleSelectAll()
    {
        $paginatedIds = Product::paginate(5)->pluck('id')->toArray();

        if (count(array_intersect($this->selectedProductId, $paginatedIds)) === count($paginatedIds)) {
            // All on this page are selected → unselect all
            $this->selectedProductId = array_diff($this->selectedProductId, $paginatedIds);
        } else {
            // Not all selected → select all on this page
            $this->selectedProductId = array_unique(array_merge($this->selectedProductId, $paginatedIds));
        }
    }

    public function editSelected()
    {
        if (!empty($this->selectedProductId)) {
            $id = is_array($this->selectedProductId) ? $this->selectedProductId[0] : $this->selectedProductId;
            return redirect()->route('customeredit', ['id' => $id]);
        }
    }
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
