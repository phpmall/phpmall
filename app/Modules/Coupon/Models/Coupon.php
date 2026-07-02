<?php

declare(strict_types=1);

namespace App\Modules\Coupon\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'coupons';

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
        'merchant_id',
        'name',
        'type',
        'scope',
        'threshold_amount',
        'discount_amount',
        'discount_rate',
        'max_discount_amount',
        'total_quantity',
        'remaining_quantity',
        'limit_per_user',
        'start_time',
        'end_time',
        'status',
    ];
}
