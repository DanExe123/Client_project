<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\ReleasedItem;
use App\Models\SalesRelease;
use App\Models\SaveReturnCredit;
use Livewire\Component;
use Livewire\WithPagination;

class Viewtransaction extends Component
{
    use WithPagination;

    public $customer;
    public $perPage = 5;

    protected $paginationTheme = 'tailwind';

    public function mount($customer)
    {
        $this->customer = Customer::findOrFail($customer);
    }

    public function render()
    {
        return view('livewire.viewtransaction', [
            'releasedItems' => ReleasedItem::where('customer_id', $this->customer->id)
                ->orderByDesc('created_at')
                ->paginate($this->perPage, ['*'], 'releasedPage'),

            'salesReleases' => SalesRelease::where('customer_id', $this->customer->id)
                ->orderByDesc('created_at')
                ->paginate($this->perPage, ['*'], 'salesPage'),

            'returnCredits' => SaveReturnCredit::where('customer_id', $this->customer->id)
                ->orderByDesc('order_date')
                ->paginate($this->perPage, ['*'], 'returnPage'),
        ]);
    }
}
