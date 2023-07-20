<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerAddressModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'seller_address';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'seller_id',
        'name',
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
        'send_status',
        'receive_status',
        'invoice_status',
        'latitude',
        'longitude',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
