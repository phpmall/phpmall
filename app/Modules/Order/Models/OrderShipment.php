<?php

declare(strict_types=1);

namespace App\Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'order_shipments';

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'merchant_id',
        'logistics_company',
        'tracking_no',
        'remark',
    ];
}
