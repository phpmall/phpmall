<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shops';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'seller_id',
        'shop_name',
        'owner_name',
        'owner_phone',
        'owner_email',
        'store_address',
        'store_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
