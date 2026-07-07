<?php

declare(strict_types=1);

namespace App\Modules\User\Models;

use App\Modules\Coupon\Models\Coupon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCoupon extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_coupons';

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
        'user_id',
        'coupon_id',
        'status',
        'used_order_id',
        'used_at',
        'claim_time',
        'expire_time',
    ];

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }
}
