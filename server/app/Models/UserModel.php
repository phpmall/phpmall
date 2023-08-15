<?php

declare(strict_types=1);

namespace App\Models;

class UserModel extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'avatar',
        'birthday',
        'mobile',
        'mobile_verified_at',
        'password',
        'status',
        'remember_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
