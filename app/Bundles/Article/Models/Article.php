<?php

declare(strict_types=1);

namespace App\Bundles\Article\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'article_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cat_id',
        'title',
        'content',
        'author',
        'author_email',
        'keywords',
        'article_type',
        'is_open',
        'add_time',
        'file_url',
        'open_type',
        'link',
        'description',
        'created_time',
        'updated_time',
    ];

    /**
     * 文章分类关联
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCat::class, 'cat_id');
    }

    /**
     * 商品文章关联
     */
    public function goodsArticles(): HasMany
    {
        return $this->hasMany(GoodsArticle::class, 'article_id');
    }
}
