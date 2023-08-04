<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'parent_id',
        'name',
        'icon',
        'keywords',
        'description',
        'level',
        'product_count',
        'product_unit',
        'nav_status',
        'show_status',
        'sort',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
