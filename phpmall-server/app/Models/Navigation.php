<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'navigations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'parent_id',
        'type',
        'name',
        'description',
        'icon',
        'link',
        'target',
        'sort',
        'status',
        'created_at',
        'updated_at',
    ];
}
