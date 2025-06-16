<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use App\Models\Receiving;
use App\Models\ReceivedItem;
use App\Models\SupplierPayment;

class PaymentToSupplier extends Component
{
    public $filterSupplier = '';
    public $filterReceiving = '';

    public $supplierOptions = [];
    public $receivingOptions = [];
    public $selectedReceivings = [];

    public $date;
    public $amount;
    public $deduction;
    public $ewt;
    public $remarks;
    public $paymentMethod;

    public $checkBank;
    public $chequeNumber;
    public $checkDate;

    public $transferBank;
    public $referenceNumber;
    public $transactionDate;

    public function mount()
    {
        $this->date = now()->toDateString();
        $this->supplierOptions = Supplier::pluck('name', 'id')->toArray();
        $this->loadReceivings();
    }

    public function updatedFilterSupplier()
    {
        $this->loadReceivings();
    }

    public function updatedFilterReceiving()
    {
        $this->loadReceivings();
    }

    public function loadReceivings()
    {
        $this->selectedReceivings = $this->getFilteredReceivedItems()
            ->map(function ($grouped) {
                $receiving = $grouped->first()->receiving;

                return [
                    'id' => $receiving->id,
                    'number' => 'RCV-' . str_pad($receiving->id, 4, '0', STR_PAD_LEFT),
                    'date' => $receiving->order_date,
                    'amount' => $grouped->sum('subtotal'),
                ];
            })->values()->toArray();
    }

    public function getFilteredReceivedItems()
    {
        $query = ReceivedItem::with('receiving');

        if ($this->filterSupplier) {
            $query->whereHas('receiving', function ($q) {
                $q->where('supplier_id', $this->filterSupplier);
            });
        }

        if ($this->filterReceiving) {
            $query->where('receiving_id', $this->filterReceiving);
        }

        return $query->get()->groupBy('receiving_id');
    }

    public function removeReceiving($index)
    {
        unset($this->selectedReceivings[$index]);
        $this->selectedReceivings = array_values($this->selectedReceivings);
    }

    public function getTotalAmountProperty()
    {
        return collect($this->selectedReceivings)->sum('amount');
    }

    public function savePayment()
    {
        foreach ($this->selectedReceivings as $receiving) {
            try {
                SupplierPayment::create([
                    'supplier_id' => $this->filterSupplier,
                    'receiving_id' => $receiving['id'],
                    'receiving_number' => $receiving['number'],
                    'receiving_date' => $receiving['date'],
                    'receiving_amount' => $receiving['amount'],
                    'payment_date' => $this->date,
                    'amount' => $this->amount,
                    'deduction' => $this->deduction,
                    'ewt' => $this->ewt,
                    'remarks' => $this->remarks,
                    'payment_method' => $this->paymentMethod,
                    'bank' => $this->checkBank ?? $this->transferBank,
                    'cheque_number' => $this->chequeNumber,
                    'check_date' => $this->checkDate,
                    'reference_number' => $this->referenceNumber,
                    'transaction_date' => $this->transactionDate,
                ]);
            } catch (\Exception $e) {
                \Log::error('Payment save failed: ' . $e->getMessage());
                $this->dispatch('show-toast', [
                    'type' => 'error',
                    'message' => 'Error saving payment: ' . $e->getMessage()
                ]);
            }
        }

        $this->reset([
            'date',
            'amount',
            'deduction',
            'ewt',
            'remarks',
            'paymentMethod',
            'checkBank',
            'chequeNumber',
            'checkDate',
            'transferBank',
            'referenceNumber',
            'transactionDate',
            'selectedReceivings',
        ]);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Payment saved successfully!'
        ]);
    }

    public function render()
    {
        return view('livewire.payment-to-supplier');
    }
}
