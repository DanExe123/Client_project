
    <div class="w-full mx-auto space-y-6 border border-gray-200 rounded-lg p-6">
        {{-- Header --}}
        <div>
            <div class="flex justify-start">
                <h2 class="text-lg font-bold text-gray-800">Edit Recieving</h2>
            </div>
        </div>
        
        {{-- Breadcrumb --}}
        <div class="text-gray-500 flex text-start gap-3">
            <a href="{{ route('recieving') }}"><span class="text-gray-500 font-medium">Recieving</span></a>
            <x-phosphor.icons::regular.caret-right class="w-4 h-4 text-gray-500 flex shrink-0 mt-1" />
            <span class="text-gray-500 font-medium">Edit Recieving</span>
        </div>

        <hr>
        <form wire:submit.prevent="submitEditedPOs">
        {{-- Input Fields --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">PO Number</label>
                <input type="text" class="form-input border-gray-300 rounded w-full"
                    wire:model.defer="purchaseOrder.po_number" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Order Date</label>
                <input type="date" class="form-input border-gray-300 rounded w-full"
                    wire:model.defer="purchaseOrder.order_date" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Receipt Type</label>
                <input type="text" class="form-input border-gray-300 rounded w-full"
                    wire:model.defer="purchaseOrder.receipt_type" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select class="form-select border-gray-300 rounded w-full"
                    wire:model.defer="purchaseOrder.status" readonly>
                   
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                <input type="number" step="0.01" class="form-input border-gray-300 rounded w-full"
                    wire:model.defer="purchaseOrder.total_amount" />
            </div>
        </div>

        <div class="flex justify-center gap-6 mt-6">
            <a href="{{ route('recieving') }}">
                <x-button label="Cancel" primary flat class="!text-sm mt-2" />
            </a>
            <x-button spinner type="submit" primary label="Submit" class="flex justify-center !w-24" />
        </div>
    </div>
</form>
