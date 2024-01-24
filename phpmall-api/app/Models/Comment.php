<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'parent_id',
        'user_id',
        'user_name',
        'content_id',
        'comment',
        'rank',
        'user_agent',
        'ip_address',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
