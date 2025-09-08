<?php

namespace admin\product_inventories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use admin\products\Models\Product;

class ProductInventory extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'track_quantity' => 'boolean',
        'allow_backorders' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}