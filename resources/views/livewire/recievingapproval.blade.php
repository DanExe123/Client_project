<div x-data="POTable()">
    <div class="col-span-1 w-full bg-white rounded-lg border shadow-md p-5 space-y-4 mt-5 mx-auto ml-1">
        <h3 class="text-lg font-bold text-gray-800">Recieving Approval</h3>

        @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition class="mt-2">
            <x-alert :title="session('message')" icon="check-circle" color="success" positive flat
                class="!bg-green-300 !w-full" />
        </div>
    @endif

        <div class="text-gray-500 flex text-start gap-3">
            <a class="text-gray-500 font-medium" wire:navigate href="{{ route('recieving') }}">Recieving</a>
            <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
            <span class="text-gray-500 font-medium "> Recieving Approval</span>
        </div> 
        <hr>

        <!-- Inputs -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-select label="Select Transaction type" placeholder="Select some user" option-label="name" option-value="id" />
            <x-select label="Select Customer" placeholder="Select some customer" option-label="name" option-value="id" />

            <div>
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" id="date" name="date"
                    class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    x-bind:value="new Date().toISOString().split('T')[0]" />
            </div>
            <div>
                <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Discount</label>
                <input type="number" id="discount" name="discount"
                    class="block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
            </div>
        </div>

        <h4 class="text-md font-semibold text-gray-700">Products</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-700 border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-2 py-1 font-medium">Product Description</th>
                            <th class="border px-2 py-1 font-medium">Qty</th>
                            <th class="border px-2 py-1 font-medium">Unit Price</th>
                            <th class="border px-2 py-1 font-medium">Discount%</th>
                            <th class="border px-2 py-1 font-medium">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-2 py-2">{{ $item['description'] }}</td>
                                <td class="border px-2 py-2">{{ $item['quantity'] }}</td>
                                <td class="border px-2 py-2">{{ number_format($item['unit_price'], 2) }}</td>
                                <td class="border px-2 py-2">{{ $item['discount'] }}%</td>
                                <td class="border px-2 py-2">{{ number_format($item['subtotal'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Add Product Button -->
            <div class="pt-2 ml-2">
                <x-button green label="Add Product" x-on:click="addProduct()" />
            </div>

            <!-- Remarks and Submit -->
            <div class="pt-4">
                <x-textarea name="remarks" label="Remarks" placeholder="Write your remarks" />
                <form wire:submit.prevent="approve">
                    <div class="flex justify-end pt-4">
                        <x-button type="submit" blue label="Approve" />
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>

<script>
  function POTable() {
    return {
        products: [],
        addProduct() {
    this.products.push({
        barcode: '',
        quantity: 1,
        price: 0.00,
        discount: 0,
        total: 0.00
    });
},
updateTotal(index) {
    const item = this.products[index];
    const qty = parseFloat(item.quantity) || 0;
    const price = parseFloat(item.price) || 0;
    const discount = parseFloat(item.discount) || 0;
    const subtotal = qty * price;
    const discountAmount = subtotal * (discount / 100);
    item.total = subtotal - discountAmount;
}

    };
}

</script>
