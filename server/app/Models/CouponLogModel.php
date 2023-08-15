<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponLogModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupon_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'seller_id',
        'shop_id',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
