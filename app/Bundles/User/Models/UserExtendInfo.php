<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserExtendInfo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_extend_info';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'Id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'reg_field_id',
        'content',
        'created_time',
        'updated_time',
    ];
}
