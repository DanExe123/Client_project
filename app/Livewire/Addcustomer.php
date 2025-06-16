<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;

class Addcustomer extends Component
{
    public $name;
    public $email;
    public $address;
    public $contact;
    public $contact_person;
    public $term;
    public $cust_tin_number;


    protected $rules = [
        'name' => 'required|string|min:3|unique:suppliers,name',
        'email' => 'required|email|unique:customers,email',
        'address' => 'nullable|string|regex:/^[a-zA-Z0-9\s,.#-]+$/',
        'contact' => 'required|regex:/^[0-9]+$/',
        'contact_person' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
        'term' => 'nullable|regex:/^[0-9]+$/',
        'cust_tin_number' => 'nullable|regex:/^[0-9-]+$/',
    ];

    public function submit()
    {
        $this->validate();

        Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'address' => $this->address,
            'contact' => $this->contact,
            'contact_person' => $this->contact_person,
            'term' => $this->term,
            'cust_tin_number' => $this->cust_tin_number, // Assuming TIN is a field in the Customer model
            'status' => true,
        ]);

        session()->flash('message', 'Successfully Added New Customer');
        $this->reset();

        return redirect()->route('customer-master');
    }

    public function render()
    {
        return view('livewire.addcustomer');
    }
}
