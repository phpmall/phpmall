<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sellers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'merchant_id',
        'shop_id',
        'store_id',
        'user_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
