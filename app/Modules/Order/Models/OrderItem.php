<?php

declare(strict_types=1);

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_items';

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
        'order_id',
        'product_id',
        'sku_id',
        'merchant_id',
        'product_title',
        'product_image',
        'sku_specs',
        'price',
        'quantity',
        'total_amount',
        'discount_amount',
        'refund_amount',
        'refund_status',
        'is_commented',
    ];
}
