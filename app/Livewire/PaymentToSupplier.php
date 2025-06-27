<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\Receiving;
use App\Models\Payment;
use App\Models\SupplierReturn;
use App\Models\SupplierReturnItem;
use App\Models\ReceivedItem;
use Illuminate\Support\Facades\Validator;


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
    public $amount, $deduction, $ewt_amount, $remarks;

    public $paymentMethod = '';
    public $checkBank, $chequeNumber, $checkDate;
    public $transferBank, $referenceNumber, $transactionDate;

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'filterSupplier' => 'required|exists:suppliers,id',
            'paymentMethod' => 'required|string|in:Cash,Check,bank_transfer',
            'amount' => 'required|numeric|min:0.01',

            'checkBank' => 'required_if:paymentMethod,Check',
            'chequeNumber' => 'required_if:paymentMethod,Check',

            'transferBank' => 'required_if:paymentMethod,bank_transfer',
            'referenceNumber' => 'required_if:paymentMethod,bank_transfer',       

            'deduction' => 'nullable|numeric|min:0',
            'ewt_amount' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ];
    }

    public function updatedPaymentMethod($value)
    {
        $this->resetValidation(['checkBank', 'chequeNumber', 'checkDate', 'transferBank', 'referenceNumber', 'transactionDate']);
    
        if ($value === 'Check') {
            $this->transferBank = null;
            $this->referenceNumber = null;
            $this->transactionDate = null;
        } elseif ($value === 'bank_transfer') {
            $this->checkBank = null;
            $this->chequeNumber = null;
            $this->checkDate = null;
        } else {
            $this->checkBank = null;
            $this->chequeNumber = null;
            $this->checkDate = null;
            $this->transferBank = null;
            $this->referenceNumber = null;
            $this->transactionDate = null;
        }
    }
    


    public function mount()
    {
        $this->date = now()->format('Y-m-d');
        $this->transactionDate = now()->format('Y-m-d');
        $this->checkDate = now()->format('Y-m-d');
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
        return ($this->totalAmount - $this->totalReturnsAmount) 
            - ((float) $this->deduction + (float) $this->ewt_amount);
    }


    public function loadReceivedItems()
    {
        $receivedItems = ReceivedItem::where('supplier_id', $this->filterSupplier)
            ->orderBy('receiving_id')
            ->get()
            ->groupBy('receiving_id');

        $this->selectedReceived = [];

        foreach ($receivedItems as $receivingId => $items) {
            $firstItem = $items->first();

            // ✅ Get actual grand_total from DB
            $grandTotal = $firstItem->grand_total ?? 0;

            // ✅ Skip display if grand_total is zero
            if ($grandTotal <= 0) {
                continue;
            }

            $this->selectedReceived[] = [
                'id' => $receivingId,
                'created_at' => $firstItem->created_at,
                'receipt_type' => $firstItem->receipt_type ?? 'N/A',
                'grand_total' => $grandTotal,
            ];
        }

        // Returns untouched
        $this->selectedReturns = SupplierReturn::with('items')
            ->where('supplier_id', $this->filterSupplier)
            ->where('status', 'pending')
            ->get()
            ->map(function ($return) {
                return [
                    'id' => $return->id,
                    'created_at' => $return->created_at,
                    'return_type' => $return->status,
                    'remarks' => $return->remarks,
                    'total' => $return->items->sum(fn($item) => $item->quantity * $item->unit_price),
                ];
            })
            ->toArray();

        $this->addedReceivings = [];
        $this->totalAmount = 0;
        $this->addedReturns = [];
        $this->totalReturnsAmount = 0;
    }



    public function addToTotal($receivingId)
    {
        if (!in_array($receivingId, $this->addedReceivings)) {
            // Find the grand_total from $this->selectedReceived
            $grand = collect($this->selectedReceived)
                ->firstWhere('id', $receivingId)['grand_total'] ?? 0;

            $this->totalAmount += $grand;
            $this->addedReceivings[] = $receivingId;
            $this->selectedReceivedIds[] = $receivingId;
        }
    }

    public function removeFromTotal($receivingId)
    {
        if (($key = array_search($receivingId, $this->addedReceivings)) !== false) {
            $grand = collect($this->selectedReceived)
                ->firstWhere('id', $receivingId)['grand_total'] ?? 0;

            $this->totalAmount -= $grand;
            unset($this->addedReceivings[$key]);
        }

        if (($index = array_search($receivingId, $this->selectedReceivedIds)) !== false) {
            unset($this->selectedReceivedIds[$index]);
        }
    }

   

    public function savePayments()
    {
         
        $this->validate();

        if (empty($this->selectedReceivedIds)) {
            $this->addError('selectedReceivedIds', 'Please select at least one Received item.');
            return;
        }

        $totalToDeduct = $this->amount + $this->ewt_amount + $this->deduction + $this->totalReturnsAmount;

        // Save Payment
        $payment = Payment::create([
            'date' => $this->date,
            'supplier_id' => $this->filterSupplier,
            'payment_method' => $this->paymentMethod,
            'bank' => $this->paymentMethod === 'Check' ? $this->checkBank : ($this->paymentMethod === 'bank_transfer' ? $this->transferBank : null),
            'cheque_number' => $this->paymentMethod === 'Check' ? $this->chequeNumber : null,
            'check_date' => $this->paymentMethod === 'Check' ? $this->checkDate : null,
            'reference_number' => $this->paymentMethod === 'bank_transfer' ? $this->referenceNumber : null,
            'transaction_date' => $this->paymentMethod === 'bank_transfer' ? $this->transactionDate : null,
            'total_amount' => $this->totalAmount,
            'amount_paid' => $this->amount,
            'ewt_amount' => $this->ewt_amount,
            'deduction' => $this->deduction,
            'remarks' => $this->remarks,
            'received_item_ids' => json_encode($this->selectedReceivedIds),
        ]);

        // Approve selected returns
        if (!empty($this->addedReturns)) {
            $payment->returns()->attach($this->addedReturns);
            SupplierReturn::whereIn('id', $this->addedReturns)->update(['status' => 'approved', 'approved_at' => now(),]);
        }

        // Deduct from Receiving::grand_total (in order)
        $remainingAmount = $totalToDeduct;


        // Fetch one row per receiving_id
        $receivedGroups = ReceivedItem::whereIn('receiving_id', $this->selectedReceivedIds)
            ->orderByRaw("FIELD(receiving_id, " . implode(',', $this->selectedReceivedIds) . ")")
            ->get()
            ->groupBy('receiving_id');

        foreach ($this->selectedReceivedIds as $receivingId) {
            if ($remainingAmount <= 0)
                break;

            $items = $receivedGroups[$receivingId] ?? collect();
            if ($items->isEmpty())
                continue;

            // Use first item to get shared grand_total
            $firstItem = $items->first();
            $grandTotal = $firstItem->grand_total;

            if ($grandTotal <= 0)
                continue;

            if ($remainingAmount >= $grandTotal) {
                // Enough to pay full grand_total
                $remainingAmount -= $grandTotal;

                foreach ($items as $item) {
                    $item->grand_total = 0;
                    $item->save();
                }

            } else {
                // Partial payment, reduce grand_total
                $updatedTotal = round($grandTotal - $remainingAmount, 2);
                $remainingAmount = 0;

                foreach ($items as $item) {
                    $item->grand_total = $updatedTotal;
                    $item->save();
                }
            }
        }



        // Finish
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
            'paymentMethod',
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
        return view('livewire.payment-to-supplier', [
            'totalAmount' => $this->totalAmount,
            'totalReturnsAmount' => $this->totalReturnsAmount,
            'payableAmount' => $this->payableAmount,
        ]);
    }
}