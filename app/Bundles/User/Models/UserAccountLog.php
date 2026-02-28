<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserAccountLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_account_log';

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
        'user_id',
        'user_money',
        'frozen_money',
        'rank_points',
        'pay_points',
        'change_time',
        'change_desc',
        'change_type',
        'created_time',
        'updated_time',
    ];
}
