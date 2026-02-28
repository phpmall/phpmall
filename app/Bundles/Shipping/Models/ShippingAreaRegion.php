<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAreaRegion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shipping_area_region';

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
        'shipping_area_id',
        'region_id',
        'created_time',
        'updated_time',
    ];
}
