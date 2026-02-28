<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsActivity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goods_activity';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'act_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'act_name',
        'act_desc',
        'act_type',
        'goods_id',
        'product_id',
        'goods_name',
        'start_time',
        'end_time',
        'is_finished',
        'ext_info',
        'created_time',
        'updated_time',
    ];
}
