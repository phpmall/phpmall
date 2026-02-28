<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopRegion extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_region';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'region_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'region_type',
        'agency_id',
        'parent_id',
        'region_name',
        'created_time',
        'updated_time',
    ];
}
