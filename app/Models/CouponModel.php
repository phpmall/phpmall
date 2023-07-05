<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'seller_id',
        'shop_id',
        'created_at',
        'updated_at',
    ];
}
