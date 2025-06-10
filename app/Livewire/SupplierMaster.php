<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Supplier; // Ensure you have a Supplier model


class SupplierMaster extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedSupplierId = [];

    public function selectSupplier($id)
    {
        if (in_array($id, $this->selectedSupplierId)) {
            // Remove if already selected (uncheck)
            $this->selectedSupplierId = array_filter(
                $this->selectedSupplierId,
                fn($item) => $item !== $id
            );
        } else {
            // Add if not selected (check)
            $this->selectedSupplierId[] = $id;
        }
    }
    
    public function toggleSelectAll()
    {
        $paginatedIds = Supplier::paginate(5)->pluck('id')->toArray();

        if (count(array_intersect($this->selectedSupplierId, $paginatedIds)) === count($paginatedIds)) {
            // All on this page are selected → unselect all
            $this->selectedSupplierId = array_diff($this->selectedSupplierId, $paginatedIds);
        } else {
            // Not all selected → select all on this page
            $this->selectedSupplierId = array_unique(array_merge($this->selectedSupplierId, $paginatedIds));
        }
    }

    public function editSelected()
    {
        if (!empty($this->selectedSupplierId)) {
            $id = is_array($this->selectedSupplierId) ? $this->selectedSupplierId[0] : $this->selectedSupplierId;
            return redirect()->route('supplieredit', ['id' => $id]);
        }
    }

    public function deleteSelected()
    {
        if (!empty($this->selectedSupplierId)) {
            Supplier::whereIn('id', $this->selectedSupplierId)->delete();
            $this->selectedSupplierId = [];
            session()->flash('message', 'Selected suppliers deleted successfully.');
        }
    }

    public function render()
    {
        $search = $this->search;

        $suppliers = Supplier::when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('address', 'like', '%' . $search . '%')
                      ->orWhere('contact', 'like', '%' . $search . '%')
                      ->orWhere('contact_person', 'like', '%' . $search . '%');
            })
            ->paginate(5);
        return view('livewire.supplier-master', compact('suppliers'));
    }
}

