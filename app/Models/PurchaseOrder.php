<?php

namespace App\Models;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'voucher_type',
        'serie',
        'correlative',
        'date',
        'supplier_id',
        'total',
        'observation'
    ];

    protected $casts = [
        'date' => 'date',
        'total' => 'decimal:2',
    ];
    //Relación uno a muchos inversa
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    //Relacion muchos a muchos polimorfica
    public function products()
    {
        return $this->morphToMany(Product::class, 'productable')
                    ->withPivot('description','quantity', 'price', 'discount', 'tax_rate',
                    'tax_amount', 'subtotal')
                    ->withTimestamps();
    }
}
