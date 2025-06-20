<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;
use Illuminate\Validation\ValidationException;

class AddSupplier extends Component
{
    public $name;
    public $email;
    public $address;
    public $term;
    public $tin_number;
    public $contact;
    public $contact_person;

    protected $rules = [
        'name' => 'required|string|min:3|unique:suppliers,name',
        'email' => 'required|email|unique:suppliers,email',
        'address' => 'nullable|string',
        'term' => 'nullable|string',
        'tin_number' => 'nullable|string',
        'contact' => 'required|digits:11', // Must be exactly 11 digits
        'contact_person' => 'nullable|string',
    ];

    public function submit()
    {
        try {
            $this->validate();

            Supplier::create([
                'name' => $this->name,
                'email' => $this->email,
                'address' => $this->address,
                'term' => $this->term,
                'tin_number' => $this->tin_number,
                'contact' => $this->contact,
                'contact_person' => $this->contact_person,
                'status' => true,
            ]);

            session()->flash('message', 'Successfully Added New Supplier');
            $this->reset();

            return redirect()->route('supplier-master');

        } catch (ValidationException $e) {
            session()->flash('error', 'Validation failed: ' . $e->getMessage());
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.addsupplier');
    }
}
