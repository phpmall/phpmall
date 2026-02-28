<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopAutoManage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_auto_manage';

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
        'item_id',
        'type',
        'starttime',
        'endtime',
        'created_time',
        'updated_time',
    ];
}
