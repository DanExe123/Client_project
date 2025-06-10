<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;

class AddSupplier extends Component
{
    public $name;
    public $address;
    public $term;
    public $contact;
    public $contact_person;
    protected $rules = [
        'name' => 'required|string|min:3',
        'address' => 'nullable|string',
        'term' => 'nullable|string',
        'contact' => 'nullable|string',
        'contact_person' => 'nullable|string',
    ];
    public function submit()
    {
        $this->validate();

        Supplier::create([
            'name' => $this->name,
            'address' => $this->address,
            'term' => $this->term,
            'contact' => $this->contact,
            'contact_person' => $this->contact_person,
            'status' => true,
        ]);

        session()->flash('message', 'Successfully Added New Supplier');

        $this->reset();
    }

    public function render()
    {
        return view('livewire.addsupplier');
    }
}
