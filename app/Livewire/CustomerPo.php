<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerPurchaseOrder;
use App\Models\CustomerPurchaseOrderItem;

class CustomerPo extends Component
{
    public $selectedCustomerId;
    public $receiptType;
    public $poDate;

    public $remarks;

    public $products = [];
    public $allProducts = [];
    public $grandTotal = 0;
    public $purchase_discount = 0;

    public function mount()
    {
        $this->poDate = now()->toDateString();
        $this->allProducts = Product::select('id', 'description', 'price', 'barcode')->get()->toArray();
    }

    public function updatedProducts()
    {
        $this->updateGrandTotal();
    }

    public function addProduct()
    {
        $this->products[] = [
            'barcode' => '',
            'product_id' => '',
            'product_description' => '',
            'quantity' => 0,
            'price' => 0,
            'product_discount' => 0, // new field
            'total' => 0,
        ];
    }

    public function removeProduct($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products); // reindex
        $this->updateGrandTotal();
    }

    public function updatePrice($index)
    {
        $productId = $this->products[$index]['product_id'] ?? null;
        $product = collect($this->allProducts)->firstWhere('id', $productId);

        if ($product) {
            $this->products[$index]['price'] = $product['price'];
            $this->updateTotal($index);
        }
    }

    public function updateTotal($index)
    {
        $item = $this->products[$index];

        $qty = isset($item['quantity']) && is_numeric($item['quantity']) ? (float) $item['quantity'] : 0;
        $price = isset($item['price']) && is_numeric($item['price']) ? (float) $item['price'] : 0;
        $discount = isset($item['product_discount']) && is_numeric($item['product_discount']) ? (float) $item['product_discount'] : 0;

        $subtotal = ($price - $discount) * $qty;
        $this->products[$index]['total'] = max($subtotal, 0); // prevent negative totals

        $this->updateGrandTotal();
    }

    public function updateGrandTotal()
    {
        $sum = collect($this->products)->sum('total');
        $discount = is_numeric($this->purchase_discount) ? (float) $this->purchase_discount : 0;

        $this->grandTotal = max($sum - $discount, 0);
    }


    //HOYY!! DRI KA NAG UNTAT
    public function fillProductByBarcode($index)
    {
        $barcode = $this->products[$index]['barcode'] ?? null;

        if (!$barcode) return;

        $product = collect($this->allProducts)->firstWhere('barcode', $barcode);

        if ($product) {
            $this->products[$index]['product_id'] = $product['id'];
            $this->products[$index]['product_description'] = $product['description'];
            $this->products[$index]['price'] = $product['price'];
            $this->products[$index]['quantity'] = $this->products[$index]['quantity'] ?? 1;
            $this->updateTotal($index);
        } else {
            $this->products[$index]['product_id'] = '';
            $this->products[$index]['product_description'] = '';
            $this->products[$index]['price'] = 0;
            $this->products[$index]['quantity'] = 0;
            $this->products[$index]['total'] = 0;
        }
    }


    public function fillProductByDescription($index)
    {
        $description = $this->products[$index]['product_description'] ?? null;

        if (!$description) return;

        $product = collect($this->allProducts)->firstWhere('description', $description);

        if ($product) {
            $this->products[$index]['product_id'] = $product['id'];
            $this->products[$index]['barcode'] = $product['barcode'];
            $this->products[$index]['price'] = $product['price'];
            $this->products[$index]['quantity'] = $this->products[$index]['quantity'] ?? 1;
            $this->updateTotal($index);
        } else {
            $this->products[$index]['product_id'] = '';
            $this->products[$index]['barcode'] = '';
            $this->products[$index]['price'] = 0;
            $this->products[$index]['quantity'] = 0;
            $this->products[$index]['total'] = 0;
        }
    }

    public function render()
    {
        $customers = Customer::all();
        $products = Product::select('id', 'description', 'price', 'barcode')->get();
        return view('livewire.customer-po',[
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    public function resetForm()
    {
        $this->selectedCustomerId = '';
        $this->receiptType = '';
        $this->poDate = now()->toDateString();
        $this->remarks = '';
        $this->products = [];
        $this->purchase_discount = 0;
        $this->grandTotal = 0;
    }


    public function submitPO()
    {
        // Basic validation (you can customize this further)
        $this->validate([
            'selectedCustomerId' => 'required|exists:suppliers,id',
            'receiptType' => 'required|string',
            'poDate' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
        ]);

        // Create purchase order
        $purchaseOrder = CustomerPurchaseOrder::create([
            'customer_id' => $this->selectedCustomerId,
            'receipt_type' => $this->receiptType,
            'order_date' => $this->poDate,
            'remarks' => $this->remarks,
            'total_amount' => $this->grandTotal,
            'purchase_discount' => $this->purchase_discount,
            'status' => true,
        ]);

        // Loop and insert each product item
        foreach ($this->products as $item) {
            CustomerPurchaseOrderItem::create([
                'purchase_order_id' => $purchaseOrder->id,
                'product_id' => $item['product_id'],
                'product_description' => $this->getProductDescription($item['product_id']),
                'product_barcode' => $item['barcode'] ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'product_discount' => $item['product_discount'] ?? 0,
                'subtotal' => $item['total'],
            ]);
        }

        // Optional: reset form after saving
        $this->resetForm();

        $this->poDate = now()->toDateString(); // resaet date
        session()->flash('message', 'Purchase order saved successfully.');
    }

    protected function getProductDescription($productId)
    {
        $product = collect($this->allProducts)->firstWhere('id', $productId);
        return $product ? $product['description'] : '';
    }
}
