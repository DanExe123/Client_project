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
    public $cust_tin_number; // Assuming TIN is a field in the Customer model

    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email',
        'address' => 'nullable|string',
        'contact' => 'required|numeric|min:0',
        'contact_person' => 'nullable|string',
        'term' => 'nullable|numeric|min:0',
        'cust_tin_number' => 'nullable|string', // Assuming TIN is optional
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
