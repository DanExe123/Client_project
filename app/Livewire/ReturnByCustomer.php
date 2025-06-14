<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerReturn;
use App\Models\CustomerReturnItem;
use App\Models\CustomerPurchaseOrderItem;
use App\Models\CustomerPurchaseOrder;

class ReturnByCustomer extends Component
{

    public $search = '';

    public $selectedCustomerId;
    public $poDate;
    public $remarks;

    public $products = [];
    public $allProducts = [];
    public $grandTotal = 0;

    public $formKey;

    public function mount()
    {
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

        // Get all products purchased by this customer
        $purchasedProducts = CustomerPurchaseOrderItem::whereHas('purchaseOrder', function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
        })
        ->with('product') // eager-load product
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->product->id,
                'description' => $item->product->description,
                'price' => $item->product->price,
                'barcode' => $item->product->barcode,
            ];
        })
        ->unique('id') // only unique products
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
            'price' => 0,
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
            $this->products[$index]['price'] = $product['price']; // Set price
            $this->updateTotal($index); // Recalculate total for that item
        }
    }

    // Recalculate total for a product row
    public function updateTotal($index)
    {
        $item = $this->products[$index];

        // Safely convert values to float, fallback to 0 if not numeric
        $qty = isset($item['quantity']) && is_numeric($item['quantity']) ? (float) $item['quantity'] : 0;
        $price = isset($item['price']) && is_numeric($item['price']) ? (float) $item['price'] : 0;

        // Calculate subtotal with discount applied
        $subtotal = $price * $qty;

        // Ensure total is not negative
        $this->products[$index]['total'] = max($subtotal, 0);

        $this->updateGrandTotal(); // Recalculate grand total
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
            $this->products[$index]['price'] = $product['price'];
            $this->products[$index]['quantity'] = $this->products[$index]['quantity'] ?? 1;
            $this->updateTotal($index); // Update row total
        } else {
            // Reset if barcode not found
            $this->products[$index]['product_id'] = '';
            $this->products[$index]['product_description'] = '';
            $this->products[$index]['price'] = 0;
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
            $this->products[$index]['price'] = $product['price'];
            $this->products[$index]['quantity'] = $this->products[$index]['quantity'] ?? 1;
            $this->updateTotal($index); // Update row total
        } else {
            // Reset if description not found
            $this->products[$index]['product_id'] = '';
            $this->products[$index]['barcode'] = '';
            $this->products[$index]['price'] = 0;
            $this->products[$index]['quantity'] = 0;
            $this->products[$index]['total'] = 0;
        }
    }

    public function render()
    {
        $search = $this->search;

        $customers = Customer::all();
        $products = Product::select('id', 'description', 'price', 'barcode')->get();
    
        /* 
        $returnOrders = CustomerReturn::with('customer') // Eager load relationship
            ->when($search, function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->paginate(5);
        */
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
        $this->selectedCustomerId = '';
        $this->poDate = now()->toDateString();
        $this->remarks = '';
        $this->products = [];
        $this->grandTotal = 0;
    }


    public function submitReturn()
    {
        // Basic validation (you can customize this further)
        $this->validate([
            'selectedCustomerId' => 'required|exists:suppliers,id',
            'poDate' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
        ]);

        // Create purchase order
        $returnOrder = CustomerReturn::create([
            'customer_id' => $this->selectedCustomerId,
            'order_date' => $this->poDate,
            'remarks' => $this->remarks,
            'total_amount' => $this->grandTotal,
            'status' => 'pending',
        ]);

        // Loop and insert each product item
        foreach ($this->products as $item) {
            CustomerReturnItem::create([
                'return_id' => $returnOrder->id,
                'product_id' => $item['product_id'],
                'product_description' => $this->getProductDescription($item['product_id']),
                'product_barcode' => $item['barcode'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'subtotal' => $item['total'],
            ]);
        }
        /* 
        $purchaseOrder->update([
            'po_number' => 'PO-' . str_pad($purchaseOrder->id, 6, '0', STR_PAD_LEFT),
        ]);
        */

        $this->resetForm(); // or $this->reset(...)
        $this->formKey = uniqid(); // triggers rerender of only that block

        $this->poDate = now()->toDateString(); // resaet date
        session()->flash('message', 'Return by customer saved successfully.');
    }

    protected function getProductDescription($productId)
    {
        $product = collect($this->allProducts)->firstWhere('id', $productId);
        return $product ? $product['description'] : '';
    }

}
