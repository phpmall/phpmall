<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserRank extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_rank';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'rank_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rank_name',
        'min_points',
        'max_points',
        'discount',
        'show_price',
        'special_rank',
        'created_time',
        'updated_time',
    ];
}
