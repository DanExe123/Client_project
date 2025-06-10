<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product; // Make sure you have a Product model

class ProductMaster extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedProductId = [];

    public function selectProduct($id)
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
            return redirect()->route('productedit', ['id' => $id]);
        }
    }

    public function deleteSelected()
    {
        if (!empty($this->selectedProductId)) {
            Product::whereIn('id', $this->selectedProductId)->delete();
            $this->selectedProductId = [];
            session()->flash('message', 'Selected Products deleted successfully.');
        }
    }

    public function render()
    {
        $search = $this->search;

        $products = Product::when($search, function ($query) use ($search) {
            $query->where('barcode', 'like', '%' . $search . '%')
                  ->orWhere('supplier', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('highest_uom', 'like', '%' . $search . '%')
                  ->orWhere('lowest_uom', 'like', '%' . $search . '%')
                  ->orWhere('price', 'like', '%' . $search . '%');
        })->paginate(5);
        return view('livewire.product-master', compact('products'));
    }
}