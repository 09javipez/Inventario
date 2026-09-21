<?php

namespace App\Models;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'voucher_type',
        'serie',
        'correlative',
        'date',
        'purchase_order_id',
        'supplier_id',
        'warehouse_id',
        'total',
        'observation'
    ];
        protected $casts = [
        'date' => 'datetime',
        'total' => 'decimal:2',
    ];

    //Relación uno a muchos inversa
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    //Relacion muchos a muchos polimorfica
    public function products()
    {
        return $this->morphToMany(Product::class, 'productable')
                    ->withPivot('description','quantity', 'price', 'discount', 'tax_rate',
                    'tax_amount', 'subtotal')
                    ->withTimestamps();
    }
    //Relacion uno a muchos polimorfica
    public function inventories()
    {
        return $this->morphMany(Inventory::class, 'inventoryable');
    }
}
