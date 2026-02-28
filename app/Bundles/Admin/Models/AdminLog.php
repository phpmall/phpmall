<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_log';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'log_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'log_time',
        'user_id',
        'log_info',
        'ip_address',
        'created_time',
        'updated_time',
    ];
}
