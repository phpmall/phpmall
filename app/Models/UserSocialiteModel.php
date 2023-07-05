<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocialiteModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_socialites';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'type',
        'identifier',
        'credentials',
        'remember_token',
        'reset_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
