<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'content_models';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'code',
        'intro',
        'fields',
        'immutable',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
