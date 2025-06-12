<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ExpenseTable;
use Carbon\Carbon;
use Livewire\WithPagination;

class Expenses extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind'; 

    public $allExpenses; 
    public $expenses = [
        'date' => '',
        'category' => '',
        'payee' => '',
        'description' => '',
        'amount' => '',
        'paid_by' => '',
        'remarks' => '',
    ];

    public function mount()
    {
        $this->expenses['date'] = Carbon::now()->toDateString();
        $this->loadExpenses();
    }
    
    public function loadExpenses()
    {
        $this->allExpenses = ExpenseTable::orderBy('date', 'desc')->get()->toArray();
    }

    public function addExpenses()
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
    
        ExpenseTable::create($validated['expenses']);
    
        // Reset all input fields
        $this->reset('expenses');
        $this->dispatch('expenses-added');
    
        // Reassign today's date
        $this->expenses['date'] = Carbon::now()->toDateString();
    
        // Refresh the list of expenses
        $this->loadExpenses();
    
        session()->flash('message', 'Expense added successfully!');
    }
    
    
    public function render()
    {
        return view('livewire.expenses', [
            'allExpenses' => ExpenseTable::orderBy('date', 'desc')->paginate(5),
        ]);
    }

    
    
    
}
