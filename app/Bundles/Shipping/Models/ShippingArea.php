<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingArea extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shipping_area';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'shipping_area_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shipping_area_name',
        'shipping_id',
        'configure',
        'created_time',
        'updated_time',
    ];
}
