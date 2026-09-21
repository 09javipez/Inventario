<?php

namespace App\Models;

use App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'voucher_type',
        'serie',
        'correlative',
        'date',
        'customer_id',
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
    //Relacion muchos a muchos polimorfica
    public function products()
    {
        return $this->morphToMany(product::class, 'productable')
                    ->withPivot('description','quantity', 'price', 'discount', 'tax_rate',
                    'tax_amount', 'subtotal')
                    ->withTimestamps();
    }
}
