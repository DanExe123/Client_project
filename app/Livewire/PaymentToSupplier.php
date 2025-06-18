<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\Receiving;
use App\Models\Payment;
use App\Models\SupplierReturn;
use App\Models\SupplierReturnItem;


class PaymentToSupplier extends Component
{

    public $selectedReturns = [];
    public $addedReturns = [];
    public $totalReturnsAmount = 0;
    public $date;
    public $filterSupplier;
    public $supplierOptions = [];
    public $selectedReceived = [];
    public $selectedReceivedIds = [];
    public $totalAmount = 0;
    public $addedReceivings = [];
    public $PaymentMethod;
    public $checkBank, $chequeNumber, $checkDate;
    public $transferBank, $referenceNumber, $transactionDate;
    public $amount, $deduction = 0, $ewt_amount = 0, $remarks;

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->supplierOptions = Supplier::pluck('name', 'id')->toArray();
    }

    public function updatedFilterSupplier($supplierId)
    {
        $this->reset(['selectedReceived', 'selectedReturns', 'addedReceivings', 'addedReturns', 'totalAmount', 'totalReturnsAmount', 'selectedReceivedIds', 'amount']);
        $this->loadReceivedItems();
    }
    public function addReturnToTotal($returnId)
    {
        if (!in_array($returnId, $this->addedReturns)) {
            $return = SupplierReturn::with('items')->find($returnId);
            $amount = $return->items->sum(fn($item) => $item->quantity * $item->unit_price);

            $this->addedReturns[] = $returnId;
            $this->totalReturnsAmount += $amount;
        }
    }
    public function removeReturnFromTotal($returnId)
    {
        if (($key = array_search($returnId, $this->addedReturns)) !== false) {
            $return = SupplierReturn::with('items')->find($returnId);
            $amount = $return->items->sum(fn($item) => $item->quantity * $item->unit_price);

            unset($this->addedReturns[$key]);
            $this->totalReturnsAmount -= $amount;
        }
    }
    public function getPayableAmountProperty()
    {
        return ($this->totalAmount - $this->totalReturnsAmount) - ($this->deduction + $this->ewt_amount);
    }

    public function loadReceivedItems()
    {
        // Load Receivings
        $this->selectedReceived = Receiving::where('supplier_id', $this->filterSupplier)
            ->where('grand_total', '>', 0)
            ->get()
            ->map(function ($rec) {
                return [
                    'id' => $rec->id,
                    'created_at' => $rec->created_at,
                    'receipt_type' => $rec->receipt_type,
                    'grand_total' => $rec->grand_total,
                ];
            })
            ->toArray();

        // Load Returns
        $this->selectedReturns = SupplierReturn::with('items')
            ->where('supplier_id', $this->filterSupplier)
            ->where('status', 'pending')
            ->get()
            ->map(function ($return) {
                return [
                    'id' => $return->id,
                    'created_at' => $return->created_at,
                    'return_type' => $return->status, // Or use another type field if you have one
                    'remarks' => $return->remarks,
                    'total' => $return->items->sum(fn($item) => $item->quantity * $item->unit_price),
                ];
            })
            ->toArray();

        // Reset totals
        $this->addedReceivings = [];
        $this->totalAmount = 0;

        $this->addedReturns = [];
        $this->totalReturnsAmount = 0;
    }

    public function addToTotal($id)
    {
        if (!in_array($id, $this->addedReceivings)) {
            $receiving = Receiving::find($id);

            if ($receiving) {
                $this->totalAmount += $receiving->grand_total;
                $this->addedReceivings[] = $id;
                $this->selectedReceivedIds[] = $id;
            }
        }
    }

    public function removeFromTotal($id)
    {
        if (($key = array_search($id, $this->addedReceivings)) !== false) {
            $receiving = Receiving::find($id);
            if ($receiving) {
                $this->totalAmount -= $receiving->grand_total;
                unset($this->addedReceivings[$key]);
            }
        }

        if (($index = array_search($id, $this->selectedReceivedIds)) !== false) {
            unset($this->selectedReceivedIds[$index]);
        }
    }
    public function updatedPaymentMethod()
    {
        // Reset fields based on payment method change
        $this->reset([
            'checkBank',
            'chequeNumber',
            'checkDate',
            'transferBank',
            'referenceNumber',
            'transactionDate',

        ]);
    }

    public function savePayments()
    {
        $this->validate([
            'date' => 'required|date',
            'filterSupplier' => 'required|exists:suppliers,id',
            'PaymentMethod' => 'required',
            'amount' => 'required|numeric|min:0',
        ]);

        $totalToDeduct = $this->amount + $this->ewt_amount + $this->deduction + $this->totalReturnsAmount;

        $payment = Payment::create([
            'date' => $this->date,
            'supplier_id' => $this->filterSupplier,
            'payment_method' => $this->PaymentMethod,
            'bank' => $this->PaymentMethod === 'Check' ? $this->checkBank : ($this->PaymentMethod === 'Bank Transfer' ? $this->transferBank : null),
            'cheque_number' => $this->PaymentMethod === 'Check' ? $this->chequeNumber : null,
            'check_date' => $this->PaymentMethod === 'Check' ? $this->checkDate : null,
            'reference_number' => $this->PaymentMethod === 'Bank Transfer' ? $this->referenceNumber : null,
            'transaction_date' => $this->PaymentMethod === 'Bank Transfer' ? $this->transactionDate : null,
            'total_amount' => $this->totalAmount,
            'amount_paid' => $this->amount,
            'ewt_amount' => $this->ewt_amount,
            'deduction' => $this->deduction,
            'remarks' => $this->remarks,
            'received_item_ids' => json_encode($this->selectedReceivedIds),
        ]);
        // Attach selected return records
        if (!empty($this->addedReturns)) {
            $payment->returns()->attach($this->addedReturns);
            SupplierReturn::whereIn('id', $this->addedReturns)->update(['status' => 'approved']);
        }

        // Total of grand_total from selected receivings
        $totalReceivingGrand = Receiving::whereIn('id', $this->selectedReceivedIds)->sum('grand_total');

        // Loop through and apply proportional deduction
        foreach ($this->selectedReceivedIds as $receivingId) {
            $receiving = Receiving::find($receivingId);

            if ($receiving && $totalReceivingGrand > 0) {
                $shareRatio = $receiving->grand_total / $totalReceivingGrand;
                $deductAmount = $totalToDeduct * $shareRatio;

                $receiving->grand_total = max(0, $receiving->grand_total - $deductAmount);
                $receiving->save();
            }
        }
        session()->flash('message', 'Payment saved and grand totals updated!');
        $this->reset([
            'selectedReturns',
            'addedReturns',
            'totalReturnsAmount',
            'date',
            'filterSupplier',
            'selectedReceived',
            'selectedReceivedIds',
            'totalAmount',
            'addedReceivings',
            'PaymentMethod',
            'checkBank',
            'chequeNumber',
            'checkDate',
            'transferBank',
            'referenceNumber',
            'transactionDate',
            'amount',
            'deduction',
            'ewt_amount',
            'remarks',
        ]);
        $this->date = now()->format('Y-m-d');
        $this->supplierOptions = Supplier::pluck('name', 'id')->toArray();
        $this->loadReceivedItems();
    }
    public function render()
    {
        return view('livewire.payment-to-supplier');
    }
}