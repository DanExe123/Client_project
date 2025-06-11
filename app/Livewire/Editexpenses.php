<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExpenseTable;
use Carbon\Carbon;

class Editexpenses extends Component
{
    public $expenseId;
    public $expenses = [];

    public function mount($id)
    {
        $this->expenseId = $id;
        $this->expenses = ExpenseTable::findOrFail($id)->toArray();
    }
    
    public function updateExpenses()
    {
        $validated = $this->validate([
            'expenses.date' => 'required|date',
            'expenses.category' => 'required|string|max:255',
            'expenses.payee' => 'nullable|string|max:255',
            'expenses.description' => 'nullable|string|max:500',
            'expenses.amount' => 'required|numeric',
            'expenses.paid_by' => 'nullable|string|max:255',
            'expenses.remarks' => 'nullable|string|max:1000',
        ]);

        ExpenseTable::findOrFail($this->expenseId)->update($validated['expenses']);

        session()->flash('message', 'Expense updated successfully!');

        return redirect()->route('expenses'); 
    }
    public function render()
    {
        return view('livewire.editexpenses');
    }
}
