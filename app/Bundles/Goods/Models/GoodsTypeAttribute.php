<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsTypeAttribute extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goods_type_attribute';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'attr_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cat_id',
        'attr_name',
        'attr_input_type',
        'attr_type',
        'attr_values',
        'attr_index',
        'sort_order',
        'is_linked',
        'attr_group',
        'created_time',
        'updated_time',
    ];
}
