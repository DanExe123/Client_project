<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;

class Create extends Component
{
    public $name;
    public $email;
    public $address;
    public $contact;
    public $contact_person;
    public $term;
    public $status = true;

    public $modalOpen = false; // Control modal visibility

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:customers,email',
        'address' => 'required|string|max:255',
        'contact' => 'required|string|max:255',
        'contact_person' => 'required|string|max:255',
        'term' => 'required|string|max:255',
        'status' => 'boolean',
    ];

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'email', 'address', 'contact', 'contact_person', 'term', 'status']);
        $this->modalOpen = true;
    }

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
            'status' => true,
        ]);

        session()->flash('message', 'Entry created!');

        $this->reset([
            'name', 'email', 'address', 'contact', 'contact_person', 'term'
        ]);

        $this->modalOpen = false;
    }

    public function render()
    {
        return view('livewire.create');
    }
}
