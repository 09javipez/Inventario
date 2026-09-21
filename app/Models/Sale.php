<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'voucher_type',
        'serie',
        'correlative',
        'date',
        'quote_id',
        'customer_id',
        'warehouse_id',
        'total',
        'observation'
    ];
    protected $casts = [
        'date' => 'datetime',
    ];
    //Relación uno a muchos inversa
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    //Relacion muchos a muchos polimorfica
    public function products()
    {
        return $this->morphToMany(product::class, 'productable')
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
