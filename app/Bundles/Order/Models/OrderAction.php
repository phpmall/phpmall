<?php

declare(strict_types=1);

namespace App\Bundles\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_action';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'action_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'action_user',
        'order_status',
        'shipping_status',
        'pay_status',
        'action_place',
        'action_note',
        'log_time',
        'created_time',
        'updated_time',
    ];
}
