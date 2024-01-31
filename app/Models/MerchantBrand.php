<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantBrand extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchant_brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'merchant_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
