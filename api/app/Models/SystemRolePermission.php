<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemRolePermission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_role_permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'system_role_id',
        'system_permission_id',
    ];
}
