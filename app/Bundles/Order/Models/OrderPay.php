<?php

declare(strict_types=1);

namespace App\Bundles\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPay extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_pay';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'log_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'order_amount',
        'order_type',
        'is_paid',
        'created_time',
        'updated_time',
    ];
}
