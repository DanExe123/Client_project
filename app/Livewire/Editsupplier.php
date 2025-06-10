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

    public function mount($id)
    {
        $this->supplierId = $id;

        $supplier = Supplier::findOrFail($id);

        // Pre-fill the form
        $this->name = $supplier->name;
        $this->address = $supplier->address;
        $this->term = $supplier->term;
        $this->contact = $supplier->contact;
        $this->contact_person = $supplier->contact_person;
    }

    public function updatesupplier()
    {
        $this->validate([
            'name' => 'required|string|min:3',
            'address' => 'nullable|string',
            'term' => 'nullable|string',
            'conatct' => 'required|numeric|min:0',
            'contact_person' => 'nullable|string',
        ]);

        supplier::where('id', $this->supplierId)->update([
            'name' => $this->name,
            'address' => $this->address,
            'term' => $this->term,
            'contact' => $this->contact,
            'contact_person' => $this->contact_person,
            'status' => true,
        ]);

        session()->flash('message', 'Supplier updated successfully.');

        return redirect()->route('supplier-master');
    }
    
    public function render()
    {
        return view('livewire.editsupplier');
    }
}
