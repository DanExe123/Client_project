<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\ReleasedItem;
use App\Models\SaveReturnCredit;
use App\Models\PaymentInvoice;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;

class Viewtransaction extends Component
{
    use WithPagination;

    public $customer;
    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    public function mount($customer)
    {
        $this->customer = Customer::findOrFail($customer);
    }

    public function render()
    {
        $transactions = $this->getMergedTransactionsWithBalance();

        $currentPage = request()->get('page', 1);
        $pagedData = new LengthAwarePaginator(
            $transactions->forPage($currentPage, $this->perPage),
            $transactions->count(),
            $this->perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $totalBalance = $transactions->last()['running_balance'] ?? 0;

        return view('livewire.viewtransaction', [
            'transactions' => $pagedData,
            'totalBalance' => $totalBalance,
        ]);
    }

    private function getMergedTransactionsWithBalance(): Collection
    {
        $sales = $this->getSalesReleases();
        $returns = $this->getReturnCredits();
        $payments = $this->getPayments();

        $merged = $sales->concat($returns)->concat($payments)
            ->sortBy('created_at')
            ->values();

        $running = 0;

        return $merged->map(function ($entry) use (&$running) {
            if ($entry['type'] === 'Sales') {
                $running += $entry['sales'];
            } elseif ($entry['type'] === 'Return') {
                $running -= $entry['credit'];
            } elseif ($entry['type'] === 'Payment') {
                $running -= $entry['payment'];
            }

            $entry['running_balance'] = $running;
            return $entry;
        });
    }

    private function getSalesReleases(): Collection
    {
        return ReleasedItem::where('customer_id', $this->customer->id)
            ->get(['id', 'created_at', 'total_amount'])
            ->map(fn($item) => [
                'id' => $item->id,
                'created_at' => $item->created_at,
                'type' => 'Sales',
                'reference' => 'INV-' . $item->id,
                'credit' => 0,
                'payment' => 0,
                'sales' => $item->total_amount ?? 0,
                'running_balance' => null,
            ]);
    }

    private function getReturnCredits(): Collection
    {
        return SaveReturnCredit::where('customer_id', $this->customer->id)
            ->get(['id', 'created_at', 'subtotal'])
            ->map(fn($item) => [
                'id' => $item->id,
                'created_at' => $item->created_at,
                'type' => 'Return',
                'reference' => 'RS-' . $item->id,
                'credit' => $item->subtotal ?? 0,
                'payment' => 0,
                'sales' => 0,
                'running_balance' => null,
            ]);
    }

    private function getPayments(): Collection
    {
        return PaymentInvoice::where('customer_id', $this->customer->id)
            ->get(['id', 'created_at', 'amount'])
            ->map(fn($item) => [
                'id' => $item->id,
                'created_at' => $item->created_at,
                'type' => 'Payment',
                'reference' => 'PAY-' . $item->id,
                'credit' => 0,
                'payment' => $item->amount ?? 0,
                'sales' => 0,
                'running_balance' => null,
            ]);
    }
}