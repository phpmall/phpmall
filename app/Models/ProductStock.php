<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_stocks';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'product_id',
        'sku_code',
        'price',
        'promotion_price',
        'stock',
        'low_stock',
        'sp1',
        'sp2',
        'sp3',
        'pic',
        'sale',
        'lock_stock',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
