<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsAttr extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goods_attr';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'goods_attr_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'goods_id',
        'attr_id',
        'attr_value',
        'attr_price',
        'created_time',
        'updated_time',
    ];
}
