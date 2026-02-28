<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserCart extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_cart';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'rec_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'goods_id',
        'goods_sn',
        'product_id',
        'goods_name',
        'market_price',
        'goods_price',
        'goods_number',
        'goods_attr',
        'is_real',
        'extension_code',
        'parent_id',
        'rec_type',
        'is_gift',
        'is_shipping',
        'can_handsel',
        'goods_attr_id',
        'created_time',
        'updated_time',
    ];
}
