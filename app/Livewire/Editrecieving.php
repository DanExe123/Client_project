<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PurchaseOrder;
use Carbon\Carbon;

class Editrecieving extends Component
{
    public $purchaseOrder;

    public function mount($id)
    {
        $po = PurchaseOrder::findOrFail($id);
        //$this->purchaseOrder = PurchaseOrder::findOrFail($id)->toArray();
        $this->purchaseOrder = $po->toArray();
           // Format date for HTML date input
           $this->purchaseOrder['order_date'] = Carbon::parse($po->order_date)->format('Y-m-d');
    }

    public function submitEditedPOs()
    {
        $po = PurchaseOrder::find($this->purchaseOrder['id']);

        if ($po) {
            $po->update([
                'po_number'     => $this->purchaseOrder['po_number'],
                'order_date'    => $this->purchaseOrder['order_date'],
                'receipt_type'  => $this->purchaseOrder['receipt_type'],
                'status'        => $this->purchaseOrder['status'],
                'total_amount'  => $this->purchaseOrder['total_amount'],
            ]);

            session()->flash('message', 'Purchase Order updated successfully.');
            return redirect()->route('recieving');
        }
    }

    public function render()
    {
        return view('livewire.editrecieving');
    }
}
