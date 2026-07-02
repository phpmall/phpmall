<?php

declare(strict_types=1);

namespace App\Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payments';

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
        'payment_no',
        'order_id',
        'user_id',
        'amount',
        'channel',
        'channel_app_id',
        'status',
        'paid_at',
        'transaction_id',
        'failure_reason',
        'client_ip',
        'expired_at',
        'notify_raw',
    ];
}
