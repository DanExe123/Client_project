<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\CustomerReturn;
use App\Models\CustomerReturnItem;
use App\Models\ReleasedItem;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\SaveReturnCredit;
use Livewire\Component;
use Illuminate\Support\Facades\DB; // Make sure to import DB

class CreditDebit extends Component
{
    use WithPagination;

    #[Url(as: 'search', history: true)]
    public $search = '';
    public $perPage = 5;
    public $releasedItems = [];
    public $filterCustomer = '';
    public $customerOptions = [];

    public array $selectedReturnItemIds = []; // Stores IDs of CustomerReturnItem chosen for credit
    public array $selectedReleasedItemIds = []; // Stores IDs of ReleasedItem chosen to deduct from

    // Properties for summary totals - keep these for UI display
    public $totalSelectedReleased = 0;
    public $totalSelectedReturns = 0;

    public function mount()
    {
        $this->customerOptions = Customer::pluck('name', 'id')->toArray();
        $this->loadInitialData(); // Call a method to load initial data
    }

    public function loadInitialData()
    {
        $this->loadReturnItems();
        $this->loadReleasedItems();
        $this->calculateTotals();
    }

    // --- Search and Filter Methods ---
    public function updatedSearch()
    {
        $this->resetPage(); // Reset pagination when search changes
        $this->loadReturnItems();
        $this->calculateTotals(); // Recalculate if search affects totals
    }

    public function updatedFilterCustomer()
    {
        $this->resetPage(); // Reset pagination when customer filter changes
        $this->selectedReturnItemIds = []; // Clear selected return items
        $this->selectedReleasedItemIds = []; // Clear selected released items
        $this->loadInitialData(); // Reload all data based on new customer
    }

    public function loadReleasedItems()
    {
        if (empty($this->filterCustomer)) {
            $this->releasedItems = collect(); // Use collection for consistency
            return;
        }

        $this->releasedItems = ReleasedItem::where('customer_id', $this->filterCustomer)->get();
    }

    public function loadReturnItems()
    {
        if (empty($this->filterCustomer)) {
            return;
        }

    }

    // --- Selection and Deselection Methods ---
    public function addReleasedItem($id)
    {
        if (!in_array($id, $this->selectedReleasedItemIds)) {
            $this->selectedReleasedItemIds[] = $id;
            $this->calculateTotals();
        }
    }

    public function removeReleasedItem($id)
    {
        $this->selectedReleasedItemIds = array_values(array_filter(
            $this->selectedReleasedItemIds,
            fn($itemId) => $itemId != $id
        ));
        $this->calculateTotals();
    }

    public function addToSave($id)
    {
        if (!in_array($id, $this->selectedReturnItemIds)) {
            $this->selectedReturnItemIds[] = $id;
            $this->calculateTotals(); // Recalculate totals when an item is added
        }
    }

    public function removeToSave($id)
    {
        $this->selectedReturnItemIds = array_values(array_filter(
            $this->selectedReturnItemIds,
            fn($itemId) => $itemId != $id
        ));
        $this->calculateTotals(); // Recalculate totals when an item is removed
    }

    // --- Calculation Methods ---
    public function calculateTotals()
    {
        // Calculate total for selected released items
        $this->totalSelectedReleased = ReleasedItem::whereIn('id', $this->selectedReleasedItemIds)->sum('total_amount');

        // Calculate total for selected return items
        $this->totalSelectedReturns = CustomerReturnItem::whereIn('id', $this->selectedReturnItemIds)
            ->get()
            ->sum(fn($item) => $item->unit_price * $item->quantity);
    }

    // This property is good for display in Blade
    public function getDifferenceAmountProperty()
    {
        return $this->totalSelectedReleased - $this->totalSelectedReturns;
    }

    // --- Save Credit Method ---
    public function saveCredit()
    {
        // Validation: Ensure at least one return item and one released item are selected
        if (empty($this->selectedReturnItemIds) || empty($this->selectedReleasedItemIds)) {
            session()->flash('message', 'Please select both return items and released items to save credit.');
            $this->dispatch('show-toast'); // Dispatch an event to show the toast
            return;
        }

        // Optional: Prevent saving if return amount exceeds released amount (depends on business logic)
        if ($this->totalSelectedReturns > $this->totalSelectedReleased) {
            session()->flash('message', 'Total return amount cannot exceed total released amount from selected items.');
            $this->dispatch('show-toast');
            return;
        }

        // Begin database transaction for atomicity
        DB::beginTransaction();

        try {
            $returnItemsToProcess = CustomerReturnItem::with('return')
                ->whereIn('id', $this->selectedReturnItemIds)
                ->get();

            // Get selected released items, ordered (e.g., by oldest date first for deduction)
            $releasedItemsToDeductFrom = ReleasedItem::whereIn('id', $this->selectedReleasedItemIds)
                ->orderBy('release_date', 'asc') // Or by ID, or any other logic
                ->get();

            $currentReleasedItemIndex = 0;
            $remainingReturnAmountToApply = $this->totalSelectedReturns;
            $processedReturnIds = [];

            foreach ($returnItemsToProcess as $returnItem) {
                $returnItemAmount = $returnItem->quantity * $returnItem->unit_price;

                // Loop through available released items to apply this return item's credit
                while ($returnItemAmount > 0 && $currentReleasedItemIndex < count($releasedItemsToDeductFrom)) {
                    $releasedItem = $releasedItemsToDeductFrom[$currentReleasedItemIndex];

                    // Check if the released item still has an outstanding balance
                    if ($releasedItem->total_amount <= 0) {
                        $currentReleasedItemIndex++;
                        continue; // Move to the next released item
                    }

                    // Determine the amount to deduct from the current released item
                    $deductAmount = min($returnItemAmount, $releasedItem->total_amount);

                    if ($deductAmount > 0) {
                        // Save the credit entry
                        SaveReturnCredit::create([
                            'return_id' => $returnItem->return_id,
                            'customer_id' => $returnItem->return->customer_id,
                            'product_barcode' => $returnItem->product_barcode,
                            'product_description' => $returnItem->product_description,
                            'quantity' => $returnItem->quantity, // Original quantity of return item
                            'unit_price' => $returnItem->unit_price, // Original unit price of return item
                            'subtotal' => $returnItem->subtotal, // Original subtotal of return item
                            'released_item_id' => $releasedItem->id,
                            'applied_amount' => $deductAmount, // The portion applied to this released item
                        ]);

                        // Update the released item's total amount
                        $releasedItem->total_amount -= $deductAmount;
                        $releasedItem->save();

                        $returnItemAmount -= $deductAmount; // Decrease the remaining amount of the current return item
                    }

                    // If the current released item is fully depleted, move to the next one
                    if ($releasedItem->total_amount <= 0) {
                        $currentReleasedItemIndex++;
                    }
                }
                // Keep track of this return_id for status update
                $processedReturnIds[] = $returnItem->return_id;
            }
            // Mark all related CustomerReturn records as approved
            CustomerReturn::whereIn('id', array_unique($processedReturnIds))
                ->update(['status' => 'approved']);
            DB::commit(); // Commit the transaction

            // Reset component state after successful save
            $this->selectedReturnItemIds = [];
            $this->selectedReleasedItemIds = [];
            $this->resetPage(); // Reset pagination
            $this->loadInitialData(); // Reload all necessary data

            session()->flash('message', 'Return credits saved successfully!');
            $this->dispatch('show-toast'); // Show success toast

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            session()->flash('message', 'An error occurred while saving: ' . $e->getMessage());
            $this->dispatch('show-toast'); // Show error toast
            \Log::error('CreditDebit save error: ' . $e->getMessage()); // Log the error
        }
    }


    public function render()
    {
        $returnItemsPaginated = CustomerReturnItem::with(['product', 'return'])
            ->whereHas('return', function ($q) {
                $q->where('status', 'pending'); // Only pending returns
                if (!empty($this->filterCustomer)) {
                    $q->where('customer_id', $this->filterCustomer);
                }
            })
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('product_barcode', 'like', '%' . $this->search . '%')
                        ->orWhere('product_description', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate($this->perPage);
        return view('livewire.credit-debit', compact('returnItemsPaginated'));
    }
}