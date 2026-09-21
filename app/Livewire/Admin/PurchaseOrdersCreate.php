<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\PurchaseOrder;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseOrdersCreate extends Component
{
    public $voucher_type = 1;

    public $serie='FAC001';
    public $correlative;

    public $date;

    public $supplier_id;

    public $total = 0;
    public $observation;

    public $product_id;

    public $products = [];

    public function boot()
    {
        $this->withValidator(function( $validator){
            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();

                 $html = "<ul class='text-left'>";

                foreach ($errors as $error) {
                    $html .= "<li>{$error[0]}</li>";
                }
                $html .="</ul>";

                $this->dispatch('swal',[
                    'icon' => 'error',
                    'title' => 'Error de validación',
                    'html' => $html,
                ]);
            }
        });
    }

    public function mount()
    {
       $this->correlative = ((int) PurchaseOrder::max('correlative')) + 1;
    }
    public function updatedVoucherType($value)
    {
        if($value ==1){
            $this->serie = 'FAC001';
        }else{
            $this->serie = 'BOL001';
        }

        $this->correlative =
        ((int) PurchaseOrder::where(
            'serie',
            $this->serie
        )->max('correlative')) + 1;
    }
    public function addProduct()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
        ],[],[
            'product_id' => 'producto',
        ]);

        $existing = collect($this->products)
            ->firstWhere('id', $this->product_id);

        if ($existing) {

            $this->dispatch('swal',[
                'icon' => 'warning',
                'title' => 'Producto ya agregado',
                'text' => 'El producto ya se encuentra en la lista',
            ]);
            return;
        }

        $product = Product::findOrFail($this->product_id);

        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'quantity' => 1,
            'price' => $product->price,
            'discount' => 0,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'subtotal' => $product->price,
        ];
        $this->reset('product_id');
    }
    public function save()
    {
        $this->validate([
            'voucher_type' => 'required|in:1,2',
            'date' => 'nullable|date',
            'supplier_id' => 'required|exists:suppliers,id',
            'total' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.description' => 'nullable|string|max:255',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.discount' => 'required|numeric|min:0',
            'products.*.tax_rate' => 'required|numeric|min:0',
        ],[],[
            'voucher_type' => 'tipo de comprobante',
            'supplier_id' => 'proveedor',
            'products' => 'productos',
            'observation' => 'observacion',
            'products.*.quantity' => 'cantidad',
            'products.*.price' => 'valor unitario',
            'products.*.discount' => 'descuento',
            'products.*.tax_rate' => 'iva',
        ]);

        DB::transaction(function(){
            $total = 0;

            foreach ($this->products as &$product) {

                $base = $product['quantity'] * $product['price'];
                if ($product['discount'] > $base) {
                    $product['discount'] = $base;
                }
                $subtotalWithoutTax = max(
                    $base - $product['discount'],
                    0
                );
                $taxAmount = $subtotalWithoutTax * ($product['tax_rate'] / 100);

                $subtotal = $subtotalWithoutTax + $taxAmount;

                $product['tax_amount'] = round($taxAmount, 2);
                $product['subtotal'] = round($subtotal, 2);
                $total += $product['subtotal'];
            }
            unset($product);
            $purchaseOrder = PurchaseOrder::create([
                'voucher_type' => $this->voucher_type,
                'serie' => $this->serie,
                'correlative' => $this->correlative,
                'date' => $this->date ?? now(),
                'supplier_id' => $this->supplier_id,
                'total' => $total,
                'observation' => $this->observation,
            ]);


            foreach ($this->products as $product){
                $purchaseOrder->products()->attach($product['id'],[
                    'description' => $product['description'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'tax_rate' => $product['tax_rate'],
                    'tax_amount' => $product['tax_amount'],
                    'subtotal' => $product['subtotal'],
                ]);
            }
        });
        session()->flash('swal',[
            'icon' => 'success',
            'title' => '!Bien hecho¡',
            'text' => 'Orden de compra creada con exitosamente.',
        ]);

        return redirect()->route('admin.purchase-orders.index');
    }


    public function render()
    {
        return view('livewire.admin.purchase-orders-create');
    }
}
