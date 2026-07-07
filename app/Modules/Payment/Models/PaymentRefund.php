<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRefund extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_refunds';

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
        'payment_id',
        'order_id',
        'order_refund_id',
        'amount',
        'channel',
        'status',
        'refunded_at',
        'channel_refund_id',
        'failure_reason',
    ];
}
