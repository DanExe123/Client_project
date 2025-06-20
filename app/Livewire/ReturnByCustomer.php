<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerReturn;
use App\Models\CustomerReturnItem;
use App\Models\SalesRelease;
use App\Models\SalesReleaseItem;
use Livewire\WithPagination;

class ReturnByCustomer extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
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
        $this->formKey = uniqid();
    }

    public function updatedSelectedCustomerId($customerId)
    {
        if (!$customerId) {
            $this->allProducts = [];
            return;
        }

        $latestReleaseIds = SalesRelease::where('customer_id', $customerId)
            ->orderByDesc('release_date')
            ->pluck('id');

        $purchasedProducts = SalesReleaseItem::whereIn('sales_release_id', $latestReleaseIds)
            ->with('product')
            ->get()
            ->sortByDesc(function ($item) use ($latestReleaseIds) {
                return array_search($item->sales_release_id, $latestReleaseIds->toArray());
            })
            ->unique('product_id')
            ->map(function ($item) {
                $lowestQty = $item->product->lowest_uom_quantity ?: 1;
                $convertedUnitPrice = $lowestQty > 0 ? ($item->unit_price / $lowestQty) : $item->unit_price;

                return [
                    'id' => $item->product_id,
                    'description' => $item->product->description,
                    'barcode' => $item->product->barcode,
                    'unit_price' => round($convertedUnitPrice, 2),
                    'lowest_uom_quantity' => $lowestQty,
                ];
            })
            ->values()
            ->toArray();

        $this->allProducts = $purchasedProducts;
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
            'unit_price' => 0,
            'total' => 0,
        ];
    }

    public function removeProduct($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
        $this->updateGrandTotal();
    }

    public function updatePrice($index)
    {
        $productId = $this->products[$index]['product_id'] ?? null;
        $product = collect($this->allProducts)->firstWhere('id', $productId);

        if ($product) {
            $this->products[$index]['unit_price'] = $product['unit_price'];
            $this->updateTotal($index);
        }
    }

    public function updateTotal($index)
    {
        $item = $this->products[$index];
        $qty = isset($item['quantity']) && is_numeric($item['quantity']) ? (float) $item['quantity'] : 0;
        $unit_price = isset($item['unit_price']) && is_numeric($item['unit_price']) ? (float) $item['unit_price'] : 0;
        $this->products[$index]['total'] = max($qty * $unit_price, 0);
        $this->updateGrandTotal();
    }

    public function updateGrandTotal()
    {
        $this->grandTotal = collect($this->products)->sum('total');
    }

    public function fillProductByBarcode($index)
    {
        $barcode = $this->products[$index]['barcode'] ?? null;
        $product = collect($this->allProducts)->firstWhere('barcode', $barcode);

        if ($product) {
            $this->products[$index]['product_id'] = $product['id'];
            $this->products[$index]['product_description'] = $product['description'];
            $this->products[$index]['unit_price'] = round($product['unit_price'], 2);
            $this->products[$index]['quantity'] = $this->products[$index]['quantity'] ?: 1;
            $this->updateTotal($index);
        } else {
            $this->clearProductRow($index);
        }
    }

    public function fillProductByDescription($index)
    {
        $description = $this->products[$index]['product_description'] ?? null;
        $product = collect($this->allProducts)->firstWhere('description', $description);

        if ($product) {
            $this->products[$index]['product_id'] = $product['id'];
            $this->products[$index]['barcode'] = $product['barcode'];
            $this->products[$index]['unit_price'] = round($product['unit_price'], 2);
            $this->products[$index]['quantity'] = $this->products[$index]['quantity'] ?: 1;
            $this->updateTotal($index);
        } else {
            $this->clearProductRow($index);
        }
    }

    private function clearProductRow($index)
    {
        $this->products[$index] = [
            'product_id' => '',
            'product_description' => '',
            'barcode' => '',
            'unit_price' => 0,
            'quantity' => 0,
            'total' => 0,
        ];
    }

    public function resetForm()
    {
        $this->returnType = 'Damage';
        $this->selectedCustomerId = '';
        $this->poDate = now()->toDateString();
        $this->remarks = '';
        $this->receiptType = '';
        $this->products = [];
        $this->grandTotal = 0;
        $this->formKey = uniqid();
    }

    public function submitReturn()
    {
        $this->validate([
            'returnType' => 'required|in:Good,Damage',
            'selectedCustomerId' => 'required|exists:customers,id',
            'poDate' => 'required|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
        ]);

        $returnOrder = CustomerReturn::create([
            'return_type' => $this->returnType,
            'customer_id' => $this->selectedCustomerId,
            'order_date' => $this->poDate,
            'receipt_type' => $this->receiptType,
            'remarks' => $this->remarks,
            'total_amount' => $this->grandTotal,
            'status' => 'pending',
        ]);

        foreach ($this->products as $item) {
            CustomerReturnItem::create([
                'return_id' => $returnOrder->id,
                'product_id' => $item['product_id'],
                'product_description' => $this->getProductDescription($item['product_id']),
                'product_barcode' => $item['barcode'] ?? '',
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['total'],
            ]);

            $product = Product::find($item['product_id']);
            if (!$product)
                continue;

            if ($this->returnType === 'Damage') {
                $product->increment('damages', $item['quantity']);
            } elseif ($this->returnType === 'Good') {
                $product->quantity_lowest += $item['quantity'];
                $lowestUom = $product->lowest_uom_quantity;

                if ($lowestUom > 0 && $product->quantity_lowest >= $lowestUom) {
                    $converted = intdiv($product->quantity_lowest, $lowestUom);
                    $remaining = $product->quantity_lowest % $lowestUom;
                    $product->quantity += $converted;
                    $product->quantity_lowest = $remaining;
                }

                $product->save();
            }
        }

        $this->resetForm();
        session()->flash('message', 'Return by customer saved successfully.');
    }

    protected function getProductDescription($productId)
    {
        $product = collect($this->allProducts)->firstWhere('id', $productId);
        return $product ? $product['description'] : '';
    }

    public function render()
    {
        $customers = Customer::all();
        $products = Product::select('id', 'description', 'barcode')->get();

        $returnOrders = CustomerReturn::with('customer')
            ->when(
                $this->search,
                fn($q) =>
                $q->whereHas(
                    'customer',
                    fn($subQ) =>
                    $subQ->where('name', 'like', '%' . $this->search . '%')
                )
            )
            ->latest()
            ->paginate(5);

        return view('livewire.return-by-customer', [
            'customers' => $customers,
            'products' => $products,
            'returnOrders' => $returnOrders,
        ]);
    }
}
