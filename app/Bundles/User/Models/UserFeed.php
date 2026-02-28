<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserFeed extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_feed';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'feed_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'value_id',
        'goods_id',
        'feed_type',
        'is_feed',
        'created_time',
        'updated_time',
    ];
}
