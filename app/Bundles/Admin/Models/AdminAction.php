<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_action';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'action_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'action_code',
        'relevance',
        'created_time',
        'updated_time',
    ];
}
