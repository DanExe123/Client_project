<div>
    <div class="overflow-auto rounded-lg border border-gray-200 shadow-md w-full mx-auto p-4" x-cloak>
        <h2 class="text-xl font-semibold mb-4">Expenses</h2>

        @if (session()->has('message'))
            <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition
                class="mt-2">
                <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                    class="!bg-green-300 !w-full" />
            </div>
        @endif

        <div>
            <!-- Add Entry Form -->
            <form wire:submit.prevent="addExpenses" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Date Field -->
                <div class="form-control">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" id="date" name="date" class="input input-bordered w-full"
                        wire:model="expenses.date">
                </div>

                <!-- Category Field -->
                <div class="form-control">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
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
                </div>

                <!-- Payee Field -->
                <div class="form-control">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payee</label>
                    <input type="text" wire:model="expenses.payee" class="input input-bordered w-full"
                        placeholder="Payee" required>
                </div>

                <!-- Description Field -->
                <div class="form-control">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" wire:model="expenses.description" class="input input-bordered w-full"
                        placeholder="Description" required>
                </div>

                <!-- Amount Field -->
                <div class="form-control">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount</label>
                    <input type="number" step="0.01" wire:model="expenses.amount" class="input input-bordered w-full"
                        placeholder="Amount" required>
                </div>

                <!-- Paid By Field -->
                <div class="form-control">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Paid by</label>
                    <input type="text" wire:model="expenses.paid_by" class="input input-bordered w-full"
                        placeholder="Paid by">
                </div>

                <!-- Remarks Field -->
                <div class="form-control md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                    <input type="text" wire:model="expenses.remarks" class="input input-bordered w-[390px]"
                        placeholder="Remarks">
                </div>

                <!-- Submit Button -->
                <div class="md:col-span-3 text-center">
                    <x-button spinner info label="Add Expenses" type="submit" class="!w-94" />
                </div>
            </form>
        </div>


        <hr>
        <div>
            <table class="min-w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50 sticky top-0 z-10">
                    <tr>
                        <th class="px-4 py-2 font-medium text-gray-900">Date</th>
                        <th class="px-4 py-2 font-medium text-gray-900">Category</th>
                        <th class="px-4 py-2 font-medium text-gray-900">Payee</th>
                        <th class="px-4 py-2 font-medium text-gray-900">Description</th>
                        <th class="px-4 py-2 font-medium text-gray-900">Amount</th>
                        <th class="px-4 py-2 font-medium text-gray-900">Paid by</th>
                        <th class="px-4 py-2 font-medium text-gray-900">Remarks</th>
                        <th class="px-4 py-2 font-medium text-gray-900 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @foreach($allExpenses as $expenses)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $expenses['date'] }}</td>
                            <td class="px-4 py-2">{{ $expenses['category'] }}</td>
                            <td class="px-4 py-2">{{ $expenses['payee'] }}</td>
                            <td class="px-4 py-2">{{ $expenses['description'] }}</td>
                            <td class="px-4 py-2">₱{{ number_format($expenses['amount'], 2) }}</td>
                            <td class="px-4 py-2">{{ $expenses['paid_by'] }}</td>
                            <td class="px-4 py-2">{{ $expenses['remarks'] }}</td>
                            <td class="px-4 py-2 text-center">
                                <a href="{{ route('editexpenses', $expenses['id']) }}">
                                    <x-button emerald label="Edit" />
                                </a>
                            </td>
                        </tr>
                    @endforeach


                    @if(count($allExpenses) === 0)
                        <tr>
                            <td colspan="8" class="px-4 py-4 text-center text-gray-500">No entries available.</td>
                        </tr>
                    @endif

                </tbody>
            </table>

        </div>

        <script>
            window.addEventListener('expenses-added', () => {
                location.reload();
            });
        </script>

    </div>