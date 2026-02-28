<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsVolumePrice extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goods_volume_price';

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
        'price_type',
        'goods_id',
        'volume_number',
        'volume_price',
        'created_time',
        'updated_time',
    ];
}
