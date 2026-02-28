<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityPackage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_package';

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
        'package_id',
        'goods_id',
        'product_id',
        'goods_number',
        'created_time',
        'updated_time',
    ];
}
