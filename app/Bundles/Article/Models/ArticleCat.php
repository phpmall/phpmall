<?php

declare(strict_types=1);

namespace App\Bundles\Article\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCat extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article_cat';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'cat_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'cat_name',
        'cat_type',
        'keywords',
        'cat_desc',
        'sort_order',
        'show_in_nav',
        'created_time',
        'updated_time',
    ];
}
