<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserBonus extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_bonus';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'bonus_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bonus_type_id',
        'bonus_sn',
        'user_id',
        'used_time',
        'order_id',
        'emailed',
        'created_time',
        'updated_time',
    ];
}
