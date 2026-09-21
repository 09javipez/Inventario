<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use App\Models\Identity;
use App\Models\Quote;
use App\Models\Sale;

class Customer extends Model
{
    protected $fillable = [
        'identity_id',
        'document_number',
        'name',
        'address',
        'email',
        'phone'
    ];
    //Relacion uno a muchos inversa
    public function identity()
    {
        return $this->belongsTo(Identity::class);
    }
    //Relacion de uno a muchos
    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
