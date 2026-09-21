<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\purchaseOrder;
use App\Models\Quote;
use App\Models\Image;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'sku',
        'barcode',
        'price',
        'category_id',
        'stock',
        'min_stock',
    ];
    //Accesores
    protected function image(): Attribute
    {
        return Attribute::make(get: fn() => $this->images->count()
            ? Storage::url($this->images->first()->path) : 'https://as2.ftcdn.net/v2/jpg/05/97/47/95/1000_F_597479556_7bbQ7t4Z8k3xbAloHFHVdZIizWK1PdOo.jpg'
        );
    }
    //Accesor Stock

    protected function stock(): Attribute
    {
        return Attribute::make(
            get: fn() =>
                $this->inventories()
                ->latest('id')
                ->value('quantity_balance') ?? 0
        );
    }
    //Accesor costo promedio
    protected function averageCost(): Attribute
    {
        return Attribute::make(
            get:fn() =>
                $this->inventories()
                ->latest('id')
                ->value('cost_balance') ?? 0
        );
    }
    //Accesor total inventario
    protected function inventoryTotal(): Attribute
    {
        return Attribute::make(
            get:fn()=>
                $this->inventories()
                    ->latest('id')
                    ->value('total_balance') ?? 0
        );
    }
    //Relación uno a muchos inversa
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    //Relacion uno ah muchos
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
    //Relación muchos a muchos polimórfica
    public function purchaseOrders()
    {
        return $this->morphedByMany(purchaseOrder::class, 'productable');
    }
    public function quotes()
    {
        return $this->morphedByMany(Quote::class, 'productable');
    }
    //Relación polimórfica
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
