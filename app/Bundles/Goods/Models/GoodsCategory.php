<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodsCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goods_category';

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
        'cat_name',
        'keywords',
        'cat_desc',
        'parent_id',
        'sort_order',
        'template_file',
        'measure_unit',
        'show_in_nav',
        'style',
        'is_show',
        'grade',
        'filter_attr',
        'created_time',
        'updated_time',
    ];

    /**
     * 父分类关联
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(GoodsCategory::class, 'parent_id', 'cat_id');
    }

    /**
     * 子分类关联
     */
    public function children(): HasMany
    {
        return $this->hasMany(GoodsCategory::class, 'parent_id', 'cat_id');
    }

    /**
     * 商品关联
     */
    public function goods(): HasMany
    {
        return $this->hasMany(Goods::class, 'cat_id');
    }

    /**
     * 推荐分类关联
     */
    public function recommendCategories(): HasMany
    {
        return $this->hasMany(GoodsCatRecommend::class, 'cat_id');
    }
}
