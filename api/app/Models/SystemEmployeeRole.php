<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemEmployeeRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_employee_roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'system_employee_id',
        'system_role_id',
    ];
}
