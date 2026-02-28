<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsMemberPrice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goods_member_price';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'price_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'goods_id',
        'user_rank',
        'user_price',
        'created_time',
        'updated_time',
    ];
}
