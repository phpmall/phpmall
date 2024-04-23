<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemEmployee extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_employees';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'status',
    ];
}
