<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_group';

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
        'parent_id',
        'goods_id',
        'goods_price',
        'created_time',
        'updated_time',
    ];
}
