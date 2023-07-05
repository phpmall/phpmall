<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductMemberPriceModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_member_prices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'product_id',
        'member_level_id',
        'member_level_name',
        'member_discount',
        'member_price',
    ];
}
