<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;

class Editcustomer extends Component
{
    public $customerId;
    public $name, $email, $address, $contact, $contact_person, $term;
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
        $this->status = $customer->status;
    }

    public function updateCustomer()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'contact' => 'nullable|string',
            'contact_person' => 'nullable|string',
            'term' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        Customer::where('id', $this->customerId)->update([
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'contact' => $this->contact,
            'contact_person' => $this->contact_person,
            'term' => $this->term,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Customer updated successfully.');

        return redirect()->route('customer-master');
    }

    public function render()
    {
        return view('livewire.editcustomer');
    }
}
