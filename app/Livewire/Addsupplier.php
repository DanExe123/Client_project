<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;

class AddSupplier extends Component
{
    public $name;
    public $address;
    public $term;
    public $tin_number; // Assuming TIN is a field in the Supplier model
    public $contact;
    public $contact_person;
    protected $rules = [
        'name' => 'required|string|min:3',
        'address' => 'nullable|string',
        'term' => 'nullable|string',
        'tin_number' => 'nullable|string', // Assuming TIN is optional
        'contact' => 'required|numeric|min:0',
        'contact_person' => 'nullable|string',
    ];
    public function submit()
    {
        $this->validate();

        Supplier::create([
            'name' => $this->name,
            'address' => $this->address,
            'term' => $this->term,
            'tin_number' => $this->tin_number, // Assuming TIN is a field in the Supplier model
            'contact' => $this->contact,
            'contact_person' => $this->contact_person,
            'status' => true,
        ]);

        session()->flash('message', 'Successfully Added New Supplier');

        $this->reset();
        return redirect()->route('supplier-master');
    }

    public function render()
    {
        return view('livewire.addsupplier');
    }
}
