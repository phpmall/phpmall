<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'guard',
        'parent_id',
        'name',
        'path',
        'icon',
        'type',
        'sort',
        'status',
        'created_at',
        'updated_at',
    ];
}
