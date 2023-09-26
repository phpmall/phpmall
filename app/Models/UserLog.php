<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'level',
        'message',
        'user_agent',
        'ip_address',
        'created_at',
        'updated_at',
    ];
}
