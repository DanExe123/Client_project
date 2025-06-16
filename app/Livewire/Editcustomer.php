<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;

class Editcustomer extends Component
{
    public $customerId;
    public $name, $email, $address, $contact, $contact_person, $term, $cust_tin_number;
    public $status;

    public function mount($id)
    {
        $this->customerId = $id;

        $customer = Customer::findOrFail($id);

        // Pre-fill the form
        $this->name = $customer->name;
        $this->email = $customer->email;
        $this->address = $customer->address;
        $this->contact = $customer->contact;
        $this->contact_person = $customer->contact_person;
        $this->term = $customer->term;
        $this->cust_tin_number = $customer->cust_tin_number;
        $this->status = $customer->status;
    }

    public function updateCustomer()
    {
        $this->validate([
            'name' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/|min:3|unique:customers,name',
            'email' => 'required|email|unique:customers,email,' . $this->customerId,
            'address' => 'nullable|string|regex:/^[a-zA-Z0-9\s,.#-]+$/',
            'contact' => 'required|regex:/^[0-9]+$/',
            'contact_person' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
            'term' => 'nullable|regex:/^[0-9]+$/',
            'cust_tin_number' => 'nullable|regex:/^[0-9-]+$/',
            'status' => 'required|boolean',
        ]);

        Customer::where('id', $this->customerId)->update([
            e
        ]);

        session()->flash('message', 'Customer updated successfully.');

        return redirect()->route('customer-master');
    }

    public function render()
    {
        return view('livewire.editcustomer');
    }
}
