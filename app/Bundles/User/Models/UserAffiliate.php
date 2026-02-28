<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserAffiliate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_affiliate';

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
        'order_id',
        'time',
        'user_id',
        'user_name',
        'money',
        'point',
        'separate_type',
        'created_time',
        'updated_time',
    ];
}
