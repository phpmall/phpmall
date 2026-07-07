<?php

declare(strict_types=1);

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSku extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_skus';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'merchant_id',
        'sku_code',
        'sku_specs',
        'price',
        'market_price',
        'cost_price',
        'stock',
        'stock_alarm',
        'weight',
        'image',
        'sales_count',
        'status',
    ];
}
