<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_role';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'role_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_name',
        'action_list',
        'role_describe',
        'created_time',
        'updated_time',
    ];
}
