<?php

namespace App\Livewire\Admin;

use App\Facades\Kardex;
use App\Models\Inventory;
use App\Models\Movement;
use App\Models\Product;
use App\Services\KardexService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MovementCreate extends Component
{
    public $type = 1;

    public $serie='ENT001';
    public $correlative;

    public $date;

    public $warehouse_id;

    public $reason_id;

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
       $this->correlative = ((int) Movement::max('correlative')) + 1;
    }
    public function updated($property, $value)
    {
        if ($property == 'type') {
            $this->reset('reason_id');
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

        $lastProductable = DB::table('productables')
            ->where('product_id', $product->id)
            ->where('productable_type', 'App\Models\Purchase')
            ->orderByDesc('id')
            ->first();
        $lastRecord = Inventory::where('product_id', $product->id)
                ->where('warehouse_id', $this->warehouse_id)
                ->latest('id')
                ->first();
        $costBalance = $lastRecord?->cost_balance ?? $product->price;
        $this->products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'quantity' => 1,
            'price' => $costBalance,
            'discount' => $lastProductable->discount ?? 0,
            'tax_rate' => $lastProductable->tax_rate ?? 0,
            'tax_amount' => 0,
            'subtotal' => $costBalance,
        ];
        $this->reset('product_id');
    }
    public function save()
    {
        $this->validate([
            'type' => 'required|in:1,2',
            'serie' => 'required|string|max:10',
            'correlative' => 'required|numeric|min:1',
            'date' => 'nullable|date',
            'warehouse_id' => 'required|exists:warehouses,id',
            'reason_id' => 'required|exists:reasons,id',
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
            'type' => 'tipo de movimiento',
            'warehouse_id' => 'almacen',
            'reason_id' => 'motivo',
            'products' => 'productos',
            'observation' => 'observacion',
            'products.*.quantity' => 'cantidad',
            'products.*.price' => 'valor unitario',
            'products.*.discount' => 'descuento',
            'products.*.tax_rate' => 'iva',
        ]);

        DB::transaction(function() {
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
            $movement = Movement::create([
                'type' => $this->type,
                'serie' => $this->serie,
                'correlative' => $this->correlative,
                'date' => $this->date ?? now(),
                'warehouse_id' => $this->warehouse_id,
                'total' => round($total, 2),
                'observation' => $this->observation,
                'reason_id' => $this->reason_id,
            ]);


            foreach ($this->products as $product){
                $movement->products()->attach($product['id'],[
                    'description' => $product['description'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'tax_rate' => $product['tax_rate'],
                    'tax_amount' => $product['tax_amount'],
                    'subtotal' => $product['subtotal'],
                ]);
                if ($this->type == 1) {
                    Kardex::registerEntry($movement, $product, $this->warehouse_id, 'Movimiento');
                }elseif($this->type == 2){
                    Kardex::registerExit($movement, $product, $this->warehouse_id, 'Movimiento');
                }
            }
        });
        session()->flash('swal',[
            'icon' => 'success',
            'title' => '!Bien hecho¡',
            'text' => 'Movimiento creado  exitosamente.',
        ]);

        return redirect()->route('admin.movements.index');
    }


    public function render()
    {
        return view('livewire.admin.movement-create');
    }
}
