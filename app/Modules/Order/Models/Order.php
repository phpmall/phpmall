<?php

declare(strict_types=1);

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

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
        'order_no',
        'user_id',
        'merchant_id',
        'parent_order_id',
        'order_type',
        'status',
        'pay_status',
        'refund_status',
        'product_amount',
        'discount_amount',
        'freight_amount',
        'pay_amount',
        'pay_method',
        'pay_time',
        'pay_transaction_id',
        'ship_time',
        'receipt_time',
        'cancel_time',
        'cancel_reason',
        'auto_receipt_time',
        'remark',
        'source',
    ];
}
