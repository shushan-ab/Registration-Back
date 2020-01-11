<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderedProduct extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'product_quantity'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
