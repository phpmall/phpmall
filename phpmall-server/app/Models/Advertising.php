<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertising extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'advertising';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'description',
        'width',
        'height',
        'link',
        'code',
        'start_time',
        'end_time',
        'click_count',
        'sort',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
