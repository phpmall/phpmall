<?php

declare(strict_types=1);

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_refunds';

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
        'refund_no',
        'order_id',
        'order_item_id',
        'user_id',
        'merchant_id',
        'type',
        'reason',
        'reason_type',
        'description',
        'images',
        'apply_amount',
        'refund_amount',
        'status',
        'merchant_remark',
        'platform_remark',
        'return_express_company',
        'return_express_no',
        'return_ship_time',
        'merchant_receipt_time',
        'refund_time',
    ];
}
