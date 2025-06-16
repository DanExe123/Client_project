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
    public $paymentMethod;

    public $checkBank;
    public $chequeNumber;
    public $checkDate;

    public $transferBank;
    public $referenceNumber;
    public $transactionDate;
    public $selectedInvoiceIds = [];
    


    public function savePayment()
    {
        $invoicesToSave = collect($this->selectedInvoices)
            ->filter(fn($invoice) => in_array($invoice['id'], $this->selectedInvoiceIds));
    
        if ($invoicesToSave->isEmpty()) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'No invoices added to total. Please select at least one.'
            ]);
            return;
        }
    
        foreach ($invoicesToSave as $invoice) {
            try {
                PaymentInvoice::create([
                    'customer_id'       => $this->filterCustomer,
                    'sales_release_id'  => $invoice['id'],
                    'invoice_number'    => $invoice['number'],
                    'invoice_date'      => $invoice['date'],
                    'invoice_amount'    => $invoice['amount'],
                    'amount'            => $this->amount,
                    'deduction'         => $this->deduction,
                    'ewt_amount'        => $this->ewt_amount,
                    'remarks'           => $this->remarks,
                    'payment_method'    => $this->paymentMethod,
                    'bank'              => $this->checkBank ?? $this->transferBank,
                    'cheque_number'     => $this->chequeNumber,
                    'check_date'        => $this->checkDate,
                    'reference_number'  => $this->referenceNumber,
                    'transaction_date'  => $this->transactionDate,
                ]);
            } catch (\Exception $e) {
                \Log::error('Payment save failed: ' . $e->getMessage());
                $this->dispatch('show-toast', [
                    'type' => 'error',
                    'message' => 'Error saving payment: ' . $e->getMessage()
                ]);
            }
        }
    
        // Clear fields after successful save
        $this->reset([
            'date', 'amount', 'deduction', 'remarks', 'paymentMethod',
            'checkBank', 'chequeNumber', 'checkDate',
            'transferBank', 'referenceNumber', 'transactionDate',
            'selectedInvoices', 'selectedInvoiceIds'
        ]);
    
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Payment saved successfully!'
        ]);
    }
    

    public function mount()
    {
        // Load customer name options (id => name)
        $this->customerOptions = Customer::pluck('name', 'id')->toArray();

        // Load unique receipt types from SalesRelease
        $this->invoiceOptions = SalesRelease::distinct()->pluck('receipt_type')->toArray();

        // Initially load all
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
                    'amount' => $invoice->total_with_vat,
                ];
            })->toArray();
    }

    public function getFilteredSalesReleases()
    {
        $query = SalesRelease::with('customer');

        if ($this->filterCustomer) {
            $query->where('customer_id', $this->filterCustomer);
        }

        if ($this->filterInvoice) {
            $query->where('receipt_type', $this->filterInvoice);
        }

        // Exclude Sales Releases that already exist in PaymentInvoice
        $paidSalesReleaseIds = PaymentInvoice::pluck('sales_release_id')->toArray();
        $query->whereNotIn('id', $paidSalesReleaseIds);

    return $query->get();
    }

    public function removeFromTotal($id)
    {
        $this->selectedInvoiceIds = array_filter($this->selectedInvoiceIds, fn($i) => $i != $id);
        $this->selectedInvoiceIds = array_values($this->selectedInvoiceIds); // optional reindex
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
