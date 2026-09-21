<?php

namespace App\Livewire\Admin;

use App\Facades\Kardex;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PurchaseCreate extends Component
{
    public $voucher_type = 1;

    public $serie;
    public $correlative;

    public $date;

    public $purchase_order_id;

    public $supplier_id;

    public $warehouse_id;

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
        $this->updatedVoucherType($this->voucher_type);
    }
    public function updated($property, $value)
    {
       if ($property == 'purchase_order_id') {
            $purchaseOrder = PurchaseOrder::find($value);

            if ($purchaseOrder) {

                $this->voucher_type = $purchaseOrder->voucher_type;
                $this->supplier_id = $purchaseOrder->supplier_id;

                $this->products = $purchaseOrder->products->map(function($product){
                    return[
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->pivot->description,
                        'quantity' => $product->pivot->quantity,
                        'price' => $product->pivot->price,
                        'discount' => $product->pivot->discount,
                        'tax_rate' => $product->pivot->tax_rate,
                        'tax_amount' => $product->pivot->tax_amount,
                        'subtotal' => $product->pivot->subtotal,
                    ];
                })->toArray();
            }
       }
    }
    public function updatedVoucherType($value)
    {
        if($value ==1){
            $this->serie = 'FAC001';
        }else{
            $this->serie = 'BOL001';
        }

        $this->correlative =
        ((int) Purchase::where(
            'serie',
            $this->serie
        )->max('correlative')) + 1;
    }
    public function addProduct()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        ],[],[
            'product_id' => 'producto',
            'warehouse_id' => 'Almacen',
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

        $product = Product::find($this->product_id);

        $LastRecord = Kardex::getLastRecord(
            $product->id,
            $this->warehouse_id
        );

        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'quantity' => 1,
            'price' => $LastRecord['cost'],
            'discount' => 0,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'subtotal' => $LastRecord['cost'],
        ];
        $this->reset('product_id');
    }
    public function save()
    {
        $this->validate([
            'voucher_type' => 'required|in:1,2',
            'serie' => 'required|string|max:10',
            'correlative' => 'required|string|max:10',
            'date' => 'nullable|date',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
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
            'products.*.price' => 'precio',
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
            $purchase = Purchase::create([
                'voucher_type' => $this->voucher_type,
                'serie' => $this->serie,
                'correlative' => $this->correlative,
                'date' => $this->date ?? now(),
                'purchase_order_id' => $this->purchase_order_id,
                'supplier_id' => $this->supplier_id,
                'warehouse_id' => $this->warehouse_id,
                'total' => round($total, 2),
                'observation' => $this->observation,
            ]);


            foreach ($this->products as $product){
                $purchase->products()->attach($product['id'],[
                    'description' => $product['description'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'tax_rate' => $product['tax_rate'],
                    'tax_amount' => $product['tax_amount'],
                    'subtotal' => $product['subtotal'],
                ]);
                //kardex
                Kardex::registerEntry($purchase, $product, $this->warehouse_id, 'compra');

            }
        });
        session()->flash('swal',[
            'icon' => 'success',
            'title' => '!Bien hecho¡',
            'text' => 'La compra se ha creado correctamente.',
        ]);

        return redirect()->route('admin.purchases.index');
    }


    public function render()
    {
        return view('livewire.admin.purchase-create');
    }
}
