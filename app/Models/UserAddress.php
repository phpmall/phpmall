<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'consignee',
        'mobile',
        'country_name',
        'country_code',
        'province_name',
        'province_code',
        'city_name',
        'city_code',
        'district_name',
        'district_code',
        'detail_address',
        'is_default',
        'is_invoice',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
