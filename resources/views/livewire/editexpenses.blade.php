<div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6">
    <div>
        <div class="flex justify-start">
            <h2 class="text-lg font-bold text-gray-800">Expenses</h2>
        </div>
    </div>

    <div class="text-gray-500 flex text-start gap-3">
        <a class="text-gray-500 font-medium" wire:navigate href="{{ route('customer-master') }}">Expenses</a>
        <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
        <span class="text-gray-500 font-medium "> Edit Expenses</span>
    </div> 
    <hr>

    <form wire:submit.prevent="updateExpenses" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <input type="date" id="date" name="date"
            class="input input-bordered w-full"
            wire:model="expenses.date">
            
            <select wire:model="expenses.category" class="select select-bordered w-full" required>
                <option disabled value="">Select a category</option>
                <option>Expense Category</option>
                <option>Office Supplies</option>
                <option>Food</option>
                <option>Transportation</option>
                <option>Utilities</option>
                <option>Electric Bill</option>
                <option>Water Bill</option>
                <option>Internet Bill</option>
                <option>Other’s</option>
            </select>

            <input type="text" wire:model="expenses.payee" class="input input-bordered w-full" placeholder="Payee" required>

            <input type="text" wire:model="expenses.description" class="input input-bordered w-full" placeholder="Description" required>

            <input type="number" step="0.01" wire:model="expenses.amount" class="input input-bordered w-full" placeholder="Amount" required>

            <input type="text" wire:model="expenses.paid_by" class="input input-bordered w-full" placeholder="Paid by" required>

            <input type="text" wire:model="expenses.remarks" class="input input-bordered w-[390px]" placeholder="Remarks" required>
        </div>
        <hr>
        <div class="pt-2 flex justify-center gap-6">
            <a href="{{ route('expenses') }}">
                <x-button label="Cancel" primary flat class="!text-sm" />
            </a>
            <x-button spinner type="submit" primary label="Update" class="flex justify-center !w-48" />
        </div>
    </form>
</div>
