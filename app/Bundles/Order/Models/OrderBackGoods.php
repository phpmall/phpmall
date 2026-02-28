<?php

declare(strict_types=1);

namespace App\Bundles\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderBackGoods extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_back_goods';

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
        'back_id',
        'goods_id',
        'product_id',
        'product_sn',
        'goods_name',
        'brand_name',
        'goods_sn',
        'is_real',
        'send_number',
        'goods_attr',
        'created_time',
        'updated_time',
    ];
}
