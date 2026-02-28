<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitySnatch extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_snatch';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'log_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'snatch_id',
        'user_id',
        'bid_price',
        'bid_time',
        'created_time',
        'updated_time',
    ];
}
