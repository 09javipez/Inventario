<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'type',
        'serie',
        'correlative',
        'date',
        'warehouse_id',
        'total',
        'observation',
        'reason_id'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    //Relacion uno a muchos inversa
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function reason()
    {
        return $this->belongsTo(Reason::class);
    }
    //Relacion muchos a muchos polimorfica
    public function products()
    {
        return $this->morphToMany(product::class, 'productable')
                    ->withPivot('description','quantity', 'price', 'discount', 'tax_rate',
                    'tax_amount', 'subtotal')
                    ->withTimestamps();
    }
    //Relacion uno amuchos polimorfica
    public function inventories()
    {
        return $this->morphMany(Inventory::class, 'inventoryable');
    }
}
