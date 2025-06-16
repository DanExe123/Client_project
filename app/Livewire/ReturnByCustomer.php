<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerReturn;
use App\Models\CustomerReturnItem;
use App\Models\CustomerPurchaseOrderItem;
use App\Models\CustomerPurchaseOrder;
use App\Models\SalesRelease;
use App\Models\SalesReleaseItem;


class ReturnByCustomer extends Component
{

    public $search = '';
    public $returnType;

    public $selectedCustomerId;
    public $poDate;
    public $remarks;
    public $receiptType;

    public $products = [];
    public $allProducts = [];
    public $grandTotal = 0;

    public $formKey;

    public function mount()
    {
        $this->returnType = 'Damage';
        $this->poDate = now()->toDateString();
        $this->allProducts = []; // Initially empty until a customer is selected
        $this->formKey = uniqid();
    }

    public function updatedSelectedCustomerId($customerId)
    {
        if (!$customerId) {
            $this->allProducts = [];
            return;
        }

        // Get the latest sales_release_items for the customer
        $latestReleases = SalesRelease::where('customer_id', $customerId)
            ->orderByDesc('release_date')
            ->pluck('id');

        $purchasedProducts = SalesReleaseItem::whereIn('sales_release_id', $latestReleases)
            ->with('product')
            ->get()
            ->sortByDesc(function ($item) use ($latestReleases) {
                return array_search($item->sales_release_id, $latestReleases->toArray());
            })
            ->unique('product_id')
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'description' => $item->product_description,
                    'barcode' => $item->product_barcode,
                    'unit_price' => $item->unit_price,
                ];
            })
            ->values()
            ->toArray();

        $this->allProducts = $purchasedProducts;
    }

    // Called when products array is modified
    public function updatedProducts()
    {
        $this->updateGrandTotal();
    }

    // Add a new blank product entry to the form
    public function addProduct()
    {
        $this->products[] = [
            'barcode' => '',
            'product_id' => '',
            'product_description' => '',
            'quantity' => 0,
            'selling_price' => 0,
            'total' => 0,
        ];
    }

    // Remove a product row based on index
    public function removeProduct($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products); // reindex
        $this->updateGrandTotal();
    }

    // Auto-fill price when a product is selected
    public function updatePrice($index)
    {
        $productId = $this->products[$index]['product_id'] ?? null;

        // Find the product from the list of all products
        $product = collect($this->allProducts)->firstWhere('id', $productId);

        if ($product) {
            $this->products[$index]['selling_price'] = $product['selling_price']; // Set price
            $this->updateTotal($index); // Recalculate total for that item
        }
    }

    // Recalculate total for a product row
    public function updateTotal($index)
    {
        $item = $this->products[$index];
        $qty = isset($item['quantity']) && is_numeric($item['quantity']) ? (float) $item['quantity'] : 0;
        $selling_price = isset($item['selling_price']) && is_numeric($item['selling_price']) ? (float) $item['selling_price'] : 0;
        $subtotal = $selling_price * $qty;
        $this->products[$index]['total'] = max($subtotal, 0);
        $this->updateGrandTotal();
    }


    // Sum all totals from the products and apply global discount
    public function updateGrandTotal()
    {
        $sum = collect($this->products)->sum('total'); // Sum of all product totals

        // Apply discount to the sum, never go below 0
        $this->grandTotal = max($sum, 0);
    }

    // Auto-fill product fields by barcode
    public function fillProductByBarcode($index)
    {
        $barcode = $this->products[$index]['barcode'] ?? null;

        if (!$barcode)
            return;

        // Find matching product by barcode
        $product = collect($this->allProducts)->firstWhere('barcode', $barcode);

        if ($product) {
            // Fill in product details
            $this->products[$index]['product_id'] = $product['id'];
            $this->products[$index]['product_description'] = $product['description'];
            $this->products[$index]['selling_price'] = $product['selling_price'];
            $this->products[$index]['quantity'] = $this->products[$index]['quantity'] ?? 1;
            $this->updateTotal($index); // Update row total
        } else {
            // Reset if barcode not found
            $this->products[$index]['product_id'] = '';
            $this->products[$index]['product_description'] = '';
            $this->products[$index]['selling_price'] = 0;
            $this->products[$index]['quantity'] = 0;
            $this->products[$index]['total'] = 0;
        }
    }

    // Auto-fill product fields by description
    public function fillProductByDescription($index)
    {
        $description = $this->products[$index]['product_description'] ?? null;

        if (!$description)
            return;

        // Find matching product by description
        $product = collect($this->allProducts)->firstWhere('description', $description);

        if ($product) {
            // Fill in product details
            $this->products[$index]['product_id'] = $product['id'];
            $this->products[$index]['barcode'] = $product['barcode'];
            $this->products[$index]['selling_price'] = $product['selling_price'];
            $this->products[$index]['quantity'] = $this->products[$index]['quantity'] ?? 1;
            $this->updateTotal($index); // Update row total
        } else {
            // Reset if description not found
            $this->products[$index]['product_id'] = '';
            $this->products[$index]['barcode'] = '';
            $this->products[$index]['selling_price'] = 0;
            $this->products[$index]['quantity'] = 0;
            $this->products[$index]['total'] = 0;
        }
    }

    public function render()
    {
        $search = $this->search;

        $customers = Customer::all();
        $products = Product::select('id', 'description', 'selling_price', 'barcode')->get();

        $returnOrders = CustomerReturn::with('customer') // Eager load relationship
            ->when($search, function ($query) use ($search) {
                $query->whereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->paginate(5);

        return view('livewire.return-by-customer', [
            'customers' => $customers,
            'products' => $products,
            'returnOrders' => $returnOrders,
        ]);
    }

    public function resetForm()
    {
        $this->returnType = '';
        $this->selectedCustomerId = '';
        $this->poDate = now()->toDateString();
        $this->remarks = '';
        $this->products = [];
        $this->grandTotal = 0;
    }


    public function submitReturn()
    {
        // Validation
        $this->validate([
            'returnType' => 'required|in:Good,Damage',
            'selectedCustomerId' => 'required|exists:customers,id',
            'poDate' => 'required|date',
            'receiptType' => 'required|string',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
        ]);

        // Create return order
        $returnOrder = CustomerReturn::create([
            'return_type' => $this->returnType,
            'customer_id' => $this->selectedCustomerId,
            'order_date' => $this->poDate,
            'receipt_type' => $this->receiptType,
            'remarks' => $this->remarks,
            'total_amount' => $this->grandTotal,
            'status' => 'pending',
        ]);

        // Loop through items and update related tables
        foreach ($this->products as $item) {
            // Create return item entry
            CustomerReturnItem::create([
                'return_id' => $returnOrder->id,
                'product_id' => $item['product_id'],
                'product_description' => $this->getProductDescription($item['product_id']),
                'product_barcode' => $item['barcode'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['selling_price'],
                'subtotal' => $item['total'],
            ]);

            // Fetch the product
            $product = Product::find($item['product_id']);

            if (!$product)
                continue;

            // If return is Damage: add to `damages`
            if ($this->returnType === 'Damage') {
                $product->increment('damages', $item['quantity']);
            }

            // If return is Good: add to `quantity_lowest` and check conversion
            if ($this->returnType === 'Good') {
                // Step 1: Add to quantity_lowest
                $product->quantity_lowest += $item['quantity'];

                // Step 2: Check if it meets or exceeds lowest_uom_quantity
                $lowestUom = $product->lowest_uom_quantity;
                if ($lowestUom > 0 && $product->quantity_lowest >= $lowestUom) {
                    $addToQuantity = intdiv($product->quantity_lowest, $lowestUom);
                    $remainingLowest = $product->quantity_lowest % $lowestUom;

                    // Step 3: Apply the conversion
                    $product->quantity += $addToQuantity;
                    $product->quantity_lowest = $remainingLowest;
                }

                // Step 4: Save the product
                $product->save();
            }
        }

        // Reset form
        $this->resetForm();
        $this->formKey = uniqid();
        $this->poDate = now()->toDateString();

        session()->flash('message', 'Return by customer saved successfully.');
    }

    protected function getProductDescription($productId)
    {
        $product = collect($this->allProducts)->firstWhere('id', $productId);
        return $product ? $product['description'] : '';
    }

}
