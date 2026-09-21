<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'serie',
        'correlative',
        'date',
        'total',
        'observation',
        'origin_warehouse_id',
        'destination_warehouse_id'
    ];
    protected $casts = [
        'date' => 'datetime',
    ];
    //Relación uno a muchos inversa
    public function originWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'origin_warehouse_id');
    }

    public function destinationWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
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
