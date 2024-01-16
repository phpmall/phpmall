<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'parent_id',
        'in_station',
        'pattern_id',
        'pattern_code',
        'slug',
        'title',
        'keywords',
        'description',
        'author',
        'image',
        'intro',
        'content',
        'extension',
        'attachment',
        'redirect_url',
        'template_index',
        'template_list',
        'template_detail',
        'sort',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
