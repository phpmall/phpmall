<?php

declare(strict_types=1);

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_reviews';

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
        'order_item_id',
        'product_id',
        'sku_id',
        'user_id',
        'merchant_id',
        'rating',
        'content',
        'images',
        'is_anonymous',
        'is_append',
        'parent_id',
        'merchant_reply',
        'merchant_reply_at',
        'status',
    ];
}
