<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Testformtable;

class Testform extends Component
{
    public $name;
    public $email;
    public $address;

    public $contact;

    public $contact_person;

    public $term;



    protected $rules = [
        'name' => 'required|string|min:3',
        'email' => 'required|email',
        'address' => 'nullable|string',
        'contact' => 'nullable|string',
        'contact_person' => 'nullable|string',
        'term' => 'nullable|string',
    ];

    
    public function submit()
{
    $this->validate();

    Testformtable::create([
        'name' => $this->name,
        'email' => $this->email,
        'address' => $this->address,
        'contact' => $this->contact,
        'contact_person' => $this->contact_person,
        'term' => $this->term,
        'status' => true,
    ]);

    session()->flash('message', 'Entry created!');

    $this->reset();
}

    public function render()
    {
        return view('livewire.testform');
    }
}
