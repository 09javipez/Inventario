<?php

namespace App\Livewire\Admin;

use App\Facades\Kardex;
use App\Models\Movement;
use App\Models\Product;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TransferCreate extends Component
{
    public $serie='T001';
    public $correlative;

    public $date;

    public $origin_warehouse_id;

    public $destination_warehouse_id;

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
       $this->correlative = ((int) Transfer::max('correlative')) + 1;
    }
    public function updated($property, $value)
    {
        if ($property == 'origin_warehouse_id') {
            $this->reset('destination_warehouse_id');
        }
    }
    public function updatedType($value)
    {
        if($value ==1){
            $this->serie = 'ENT001';
        }else{
            $this->serie = 'SAL001';
        }

        $this->correlative =
        ((int) Movement::where(
            'serie',
            $this->serie
        )->max('correlative')) + 1;
    }
    public function addProduct()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'origin_warehouse_id' => 'required|exists:warehouses,id',
        ],[],[
            'product_id' => 'producto',
            'origin_warehouse_id' => 'almacen de origen',
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
            $this->origin_warehouse_id
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

            'serie' => 'required|string|max:10',
            'correlative' => 'required|numeric|min:1',
            'date' => 'nullable|date',
            'origin_warehouse_id' => 'required|exists:warehouses,id',
            'destination_warehouse_id' => 'required|different:origin_warehouse_id|exists:warehouses,id',
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
            'origin_warehouse_id' => 'almacen de origen',
            'destination_warehouse_id' => 'almacen de destino',
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
            $transfer = Transfer::create([
                'serie' => $this->serie,
                'correlative' => $this->correlative,
                'date' => $this->date ?? now(),
                'origin_warehouse_id' => $this->origin_warehouse_id,
                'destination_warehouse_id' => $this->destination_warehouse_id,
                'total' => round($total, 2),
                'observation' => $this->observation,
            ]);


            foreach ($this->products as $product){
                $transfer->products()->attach($product['id'],[
                    'description' => $product['description'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'tax_rate' => $product['tax_rate'],
                    'tax_amount' => $product['tax_amount'],
                    'subtotal' => $product['subtotal'],
                ]);
                Kardex::registerExit(
                    $transfer,
                    $product,
                    $this->origin_warehouse_id,
                    "Transferencia"
                );
                Kardex::registerEntry(
                    $transfer,
                    $product,
                    $this->destination_warehouse_id,
                    "Transferencia"
                );
            }
        });
        session()->flash('swal',[
            'icon' => 'success',
            'title' => '!Bien hecho¡',
            'text' => 'Transferencia creada  correctamente.',
        ]);

        return redirect()->route('admin.transfers.index');
    }


    public function render()
    {
        return view('livewire.admin.transfer-create');
    }
}
