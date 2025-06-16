<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Supplier;

class Editsupplier extends Component
{
    public $supplierId;
    public $name;
    public $address;
    public $term;
    public $contact;
    public $contact_person;
    public $tin_number;
    public $status;

    public function mount($id)
    {
        $this->supplierId = $id;

        $supplier = Supplier::findOrFail($id);

        // Pre-fill the form
        $this->name = $supplier->name;
        $this->address = $supplier->address;
        $this->contact = $supplier->contact;
        $this->contact_person = $supplier->contact_person;
        $this->term = $supplier->term;
        $this->tin_number = $supplier->tin_number;
        $this->status = $supplier->status;
    }

    public function updatesupplier()
    {
        $this->validate([          
            'name' => 'required|string||regex:/^[a-zA-Z0-9\s,.#-]+$/',
            'address' => 'nullable|string|regex:/^[a-zA-Z0-9\s,.#-]+$/',
            'contact' => 'required|regex:/^[0-9]+$/',
            'contact_person' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
            'term' => 'nullable|regex:/^[0-9]+$/',
            'tin_number' => 'nullable|regex:/^[0-9-]+$/',
            'status' => 'required|boolean',
        ]);

        supplier::where('id', $this->supplierId)->update([
            'name' => $this->name,
            'address' => $this->address,
            'contact' => $this->contact,
            'contact_person' => $this->contact_person,
            'term' => $this->term,
            'tin_number' => $this->tin_number,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Supplier updated successfully.');

        return redirect()->route('supplier-master');
    }
    
    public function render()
    {
        return view('livewire.editsupplier');
    }
}
