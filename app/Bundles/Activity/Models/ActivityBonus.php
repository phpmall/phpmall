<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityBonus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_bonus';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'type_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type_name',
        'type_money',
        'send_type',
        'min_amount',
        'max_amount',
        'send_start_date',
        'send_end_date',
        'use_start_date',
        'use_end_date',
        'min_goods_amount',
        'created_time',
        'updated_time',
    ];
}
