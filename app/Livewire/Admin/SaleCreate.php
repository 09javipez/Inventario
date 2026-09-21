<?php

namespace App\Livewire\Admin;

use App\Facades\Kardex;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SaleCreate extends Component
{
    public $voucher_type = 1;

    public $serie = 'FAC001';
    public $correlative;

    public $date;

    public $quote_id;

    public $customer_id;

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
        $this->correlative = ((int) Sale::max('correlative')) + 1;
    }
    public function updated($property, $value)
    {
       if ($property == 'quote_id') {
            $quote = Quote::find($value);

            if ($quote) {

                $this->voucher_type = $quote->voucher_type;
                $this->customer_id = $quote->customer_id;

                $this->products = $quote->products->map(function($product){
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
        ((int) Sale::where(
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
            'serie' => 'required|string|max:10',
            'correlative' => 'required|numeric|min:1',
            'date' => 'nullable|date',
            'quote_id' => 'nullable|exists:quotes,id',
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'total' => 'required|numeric|min:0',
            'observation' => 'nullable|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.description' => 'nullable|string|max:255',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.discount' => 'required|numeric|min:0',
            'products.*.tax_rate' => 'required|numeric|in:0,5,19',
        ],[],[
            'voucher_type' => 'tipo de comprobante',
            'customer_id' => 'cliente',
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
            $sale = Sale::create([
                'voucher_type' => $this->voucher_type,
                'serie' => $this->serie,
                'correlative' => $this->correlative,
                'date' => $this->date ?? now(),
                'quote_id' => $this->quote_id,
                'customer_id' => $this->customer_id,
                'warehouse_id' => $this->warehouse_id,
                'total' => round($total, 2),
                'observation' => $this->observation,
            ]);


            foreach ($this->products as $product){
                $sale->products()->attach($product['id'],[
                    'description' => $product['description'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'tax_rate' => $product['tax_rate'],
                    'tax_amount' => $product['tax_amount'],
                    'subtotal' => $product['subtotal']
                ]);
                //kardex
                Kardex::registerExit($sale, $product, $this->warehouse_id, 'venta');
            }
        });
        session()->flash('swal',[
            'icon' => 'success',
            'title' => '!Bien hecho¡',
            'text' => 'La venta se ha creado correctamente.',
        ]);

        return redirect()->route('admin.sales.index');
    }


    public function render()
    {
        return view('livewire.admin.sale-create');
    }
}
