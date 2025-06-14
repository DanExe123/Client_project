<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PurchaseOrder;
use App\Models\Recievings;

class Recieving extends Component
{
    public $search = '';
    public $purchaseOrders;
    public $selectedpoId = [];
    public $cancelId = null;
    public $statusTab = 'For Approval';
    public $forApprovalOrders = [];
    public $approvedOrders = [];
    public $cancelledOrders = [];
    
    

    public function mount($id = null)
{
    if ($id) {
        $this->cancelPurchaseOrder($id);
    }

    $this->forApprovalOrders = PurchaseOrder::with('supplier')
        ->where('status', 'Pending')
        ->get();

    $this->approvedOrders = PurchaseOrder::with('supplier')
        ->where('status', 'Approved')
        ->get();

    $this->cancelledOrders = PurchaseOrder::with('supplier')
        ->where('status', 'Cancelled')
        ->get();

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


        public function cancelPurchaseOrder($id)
        {
            $po = PurchaseOrder::find($id);
        
            if (!$po) {
                session()->flash('error', 'Purchase order not found.');
                return;
            }
        
            if ($po->status === 'cancelled') {
                session()->flash('error', 'This purchase order has already been cancelled.');
                return;
            }
        
            $po->status = 'cancelled';
            $po->save();
        
            $this->selectedpoId = array_filter($this->selectedpoId, fn($item) => $item != $id);
        
            session()->flash('message', 'Purchase order cancelled successfully.');
        }
        

    public function render()
    {
        return view('livewire.recieving', [
            'purchaseOrders' => $this->purchaseOrders,
            'statusTab' => $this->statusTab,
        ]);
    }
}
