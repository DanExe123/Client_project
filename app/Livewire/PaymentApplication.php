<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\SalesRelease;
use App\Models\PaymentInvoice;

class PaymentApplication extends Component
{
    public $filterCustomer = '';
    public $filterInvoice = '';

    public $customerOptions = [];
    public $invoiceOptions = [];

    public $selectedInvoices = [];

    public $date;
    public $amount;
    public $deduction;
    public $ewt_amount;
    public $remarks;
    public $paymentMethod = ''; // Initialize to empty string

    public $checkBank;
    public $chequeNumber;
    public $checkDate;

    public $transferBank;
    public $referenceNumber;
    public $transactionDate;
    public $selectedInvoiceIds = [];

    protected function rules()
    {
        return [
            'date' => 'required|date',
            'filterCustomer' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'paymentMethod' => 'required|string|in:Cash,Check,Bank Transfer',

            'checkBank' => 'required_if:paymentMethod,Check',
            'chequeNumber' => 'required_if:paymentMethod,Check',
            'checkDate' => 'required_if:paymentMethod,Check|nullable|date',

            'transferBank' => 'required_if:paymentMethod,Bank Transfer',
            'referenceNumber' => 'required_if:paymentMethod,Bank Transfer',
            'transactionDate' => 'required_if:paymentMethod,Bank Transfer|nullable|date',

            'deduction' => 'nullable|numeric|min:0',
            'ewt_amount' => 'nullable|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
        ];
    }

    public function updatedPaymentMethod($value)
    {
        // Reset fields when payment method changes
        if ($value !== 'Check') {
            $this->checkBank = null;
            $this->chequeNumber = null;
            $this->checkDate = null;
        }
        if ($value !== 'Bank Transfer') {
            $this->transferBank = null;
            $this->referenceNumber = null;
            $this->transactionDate = null;
        }
    }

    public function savePayment()
    {
        $this->amount = str_replace(',', '', $this->amount);
        $this->validate(); 

        if (empty($this->selectedInvoiceIds)) {
            $this->addError('selectedInvoiceIds', 'Please select at least one invoice.');
            return;
        }

        $totalPayment = $this->amount + $this->deduction + $this->ewt_amount;
        $remainingAmount = $totalPayment;

        $invoiceIds = collect($this->selectedInvoices)
            ->filter(fn($invoice) => in_array($invoice['id'], $this->selectedInvoiceIds))
            ->pluck('id')
            ->unique();

        $groupedInvoices = SalesRelease::with('releasedItems')
            ->whereIn('id', $invoiceIds)
            ->get();

        foreach ($groupedInvoices as $salesRelease) {
            if ($remainingAmount <= 0) break;

            $items = $salesRelease->releasedItems;
            $itemCount = $items->count();

            if ($itemCount === 0) continue;

            // All items have the same total_amount (as per your rule)
            $itemTotalAmount = $items->first()->total_amount;
            $invoiceTotalAmount = $itemTotalAmount; // NOT SUM
            $totalThisInvoice = $invoiceTotalAmount; // We treat invoice as ONE BLOCK

            if ($remainingAmount >= $totalThisInvoice) {
                // Full payment → Set ALL items to 0
                foreach ($items as $item) {
                    $item->total_amount = 0;
                    $item->save();
                }

                $paidAmount = $totalThisInvoice;
            } else {
                // Partial payment → Calculate new equal value
                $newAmount = $itemTotalAmount - $remainingAmount;
                foreach ($items as $item) {
                    $item->total_amount = round($newAmount, 2);
                    $item->save();
                }

                $paidAmount = $remainingAmount;
            }

            // Create payment invoice
            PaymentInvoice::create([
                'customer_id' => $this->filterCustomer,
                'sales_release_id' => $salesRelease->id,
                'invoice_number' => $salesRelease->id,
                'invoice_date' => $salesRelease->release_date,
                'invoice_amount' => $itemTotalAmount,
                'amount' => $paidAmount,
                'deduction' => $this->deduction,
                'ewt_amount' => $this->ewt_amount,
                'remarks' => $this->remarks,
                'payment_method' => $this->paymentMethod,
                'bank' => $this->checkBank ?? $this->transferBank,
                'cheque_number' => $this->chequeNumber,
                'check_date' => $this->checkDate,
                'reference_number' => $this->referenceNumber,
                'transaction_date' => $this->transactionDate,
            ]);

            $remainingAmount -= $paidAmount;
        }

        $this->reset([
            'date', 'amount', 'deduction', 'remarks', 'paymentMethod',
            'checkBank', 'chequeNumber', 'checkDate',
            'transferBank', 'referenceNumber', 'transactionDate',
            'selectedInvoices', 'selectedInvoiceIds'
        ]);

        session()->flash('message', 'Payment applied successfully. Items with same invoice share exact same value.');
    }

    

    public function mount()
    {
        $this->checkDate = now()->toDateString();
        $this->date = now()->toDateString();
        $this->customerOptions = Customer::pluck('name', 'id')->toArray();
        $this->invoiceOptions = SalesRelease::distinct()->pluck('receipt_type')->toArray();
        $this->loadInvoices();
    }

    public function updatedFilterCustomer()
    {
        $this->loadInvoices();
    }

    public function updatedFilterInvoice()
    {
        $this->loadInvoices();
    }

    public function loadInvoices()
    {
        $this->selectedInvoices = $this->getFilteredSalesReleases()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'number' => 'INV-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT),
                    'date' => $invoice->release_date,
                    'amount' => optional($invoice->releasedItems->first())->total_amount ?? 0, // ✅ just get from 1 item
                ];
            })->toArray();
    }

    public function getFilteredSalesReleases()
    {
        $query = SalesRelease::with(['customer', 'releasedItems']);

        if ($this->filterCustomer) {
            $query->where('customer_id', $this->filterCustomer);
        }

        if ($this->filterInvoice) {
            $query->where('receipt_type', $this->filterInvoice);
        }

        $salesReleases = $query->get();

        // 🔧 Fix here: use total_amount directly from the main table
        $unpaidOrPartiallyPaid = $salesReleases->filter(function ($release) {
            $totalAmount = $release->total_amount;

            $totalPaid = PaymentInvoice::where('sales_release_id', $release->id)->sum('amount');

            return $totalPaid < $totalAmount;
        });

        return $unpaidOrPartiallyPaid;
    }



    public function removeFromTotal($id)
    {
        $this->selectedInvoiceIds = array_filter($this->selectedInvoiceIds, fn($i) => $i != $id);
        $this->selectedInvoiceIds = array_values($this->selectedInvoiceIds);
    }

    public function addToTotal($id)
    {
        if (!in_array($id, $this->selectedInvoiceIds)) {
            $this->selectedInvoiceIds[] = $id;
        }
    }

    public function getTotalAmountProperty()
    {
        return collect($this->selectedInvoices)
            ->filter(fn($inv) => in_array($inv['id'], $this->selectedInvoiceIds))
            ->sum('amount');
    }

    public function render()
    {
        return view('livewire.payment-application', [
            'totalAmount' => $this->totalAmount,
        ]);
    }
}
