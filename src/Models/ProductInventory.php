<?php

namespace admin\product_inventories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        if (class_exists(\admin\products\Models\Product::class)) {
            return $this->belongsTo(\admin\products\Models\Product::class);
        }
    }
}
