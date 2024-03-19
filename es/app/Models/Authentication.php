<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authentication extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'authentications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_uuid',
        'type',
        'identifier',
        'credentials',
        'status',
    ];
}
