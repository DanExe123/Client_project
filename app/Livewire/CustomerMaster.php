<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class CustomerMaster extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedCustomerId = [];

    public function selectCustomer($id)
    {
        if (in_array($id, $this->selectedCustomerId)) {
            // Remove if already selected (uncheck)
            $this->selectedCustomerId = array_filter(
                $this->selectedCustomerId,
                fn($item) => $item !== $id
            );
        } else {
            // Add if not selected (check)
            $this->selectedCustomerId[] = $id;
        }
    }


    public function toggleSelectAll()
    {
        $paginatedIds = Customer::paginate(5)->pluck('id')->toArray();

        if (count(array_intersect($this->selectedCustomerId, $paginatedIds)) === count($paginatedIds)) {
            // All on this page are selected → unselect all
            $this->selectedCustomerId = array_diff($this->selectedCustomerId, $paginatedIds);
        } else {
            // Not all selected → select all on this page
            $this->selectedCustomerId = array_unique(array_merge($this->selectedCustomerId, $paginatedIds));
        }
    }

    public function editSelected()
    {
        if (!empty($this->selectedCustomerId)) {
            $id = is_array($this->selectedCustomerId) ? $this->selectedCustomerId[0] : $this->selectedCustomerId;
            return redirect()->route('customeredit', ['id' => $id]);
        }
    }

    public function deleteSelected()
    {
        if (!empty($this->selectedCustomerId)) {
            Customer::whereIn('id', $this->selectedCustomerId)->delete();
            $this->selectedCustomerId = [];
            session()->flash('message', 'Selected customers deleted successfully.');
        }
    }


    public function render()
    {
        $search = $this->search;

        $customers = Customer::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('address', 'like', '%' . $search . '%')
                ->orWhere('contact', 'like', '%' . $search . '%')
                ->orWhere('contact_person', 'like', '%' . $search . '%')
                ->orWhere('term', 'like', '%' . $search . '%')
                ->orWhere('cust_tin_number', 'like', '%' . $search . '%');
        })->paginate(5);
        return view('livewire.customer-master', compact('customers'));
    }
}
