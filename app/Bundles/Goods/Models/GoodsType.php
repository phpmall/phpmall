<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goods_type';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'cat_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cat_name',
        'enabled',
        'attr_group',
        'created_time',
        'updated_time',
    ];
}
