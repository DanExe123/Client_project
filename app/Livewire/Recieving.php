<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PurchaseOrder;

class Recieving extends Component
{
    public $search = '';
    public $purchaseOrders;
    public $selectedpoId = [];
    

    public function mount()
    {
        $this->purchaseOrders = PurchaseOrder::with('supplier')->latest()->get();
    }

            public function selectedPo($id)
        {
            if (in_array($id, $this->selectedpoId)) {
                $this->selectedpoId = array_filter($this->selectedpoId, fn($item) => $item != $id);
            } else {
                $this->selectedpoId[] = $id;
            }
        }

        public function toggleSelectAll()
        {
            $paginatedIds = $this->purchaseOrders->pluck('id')->toArray(); // from Livewire property

            if (count(array_intersect($this->selectedpoId, $paginatedIds)) === count($paginatedIds)) {
                // All selected → unselect
                $this->selectedpoId = array_diff($this->selectedpoId, $paginatedIds);
            } else {
                // Not all selected → select all
                $this->selectedpoId = array_unique(array_merge($this->selectedpoId, $paginatedIds));
            }
        }

        public function approveSelected()
        {
            if (count($this->selectedpoId) === 1) {
                $selectedId = $this->selectedpoId[0];
                return redirect()->route('recievingapproval', ['purchaseOrderId' => $selectedId]);
            }
        }
        

        public function editSelected()
        {
            if (!empty($this->selectedpoId)) {
                $id = is_array($this->selectedpoId) ? $this->selectedpoId[0] : $this->selectedpoId;
                return redirect()->route('editrecieving', ['id' => $id]);
            }
        }        

    public function render()
    {
        return view('livewire.recieving', [
            'purchaseOrders' => $this->purchaseOrders
        ]);
    }
}
