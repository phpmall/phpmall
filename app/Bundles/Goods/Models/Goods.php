<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Models;

use App\Bundles\User\Models\UserRank;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Goods extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goods';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'goods_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cat_id',
        'goods_sn',
        'goods_name',
        'goods_name_style',
        'click_count',
        'brand_id',
        'provider_name',
        'goods_number',
        'goods_weight',
        'market_price',
        'shop_price',
        'promote_price',
        'promote_start_date',
        'promote_end_date',
        'warn_number',
        'keywords',
        'goods_brief',
        'goods_desc',
        'goods_thumb',
        'goods_img',
        'original_img',
        'is_real',
        'extension_code',
        'is_on_sale',
        'is_alone_sale',
        'is_shipping',
        'integral',
        'add_time',
        'sort_order',
        'is_delete',
        'is_best',
        'is_new',
        'is_hot',
        'is_promote',
        'bonus_type_id',
        'last_update',
        'goods_type',
        'seller_note',
        'give_integral',
        'rank_integral',
        'suppliers_id',
        'is_check',
        'created_time',
        'updated_time',
    ];

    /**
     * 商品分类关联
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(GoodsCategory::class, 'cat_id');
    }

    /**
     * 品牌关联
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(GoodsBrand::class, 'brand_id');
    }

    /**
     * 商品属性关联
     */
    public function goodsAttrs(): HasMany
    {
        return $this->hasMany(GoodsAttr::class, 'goods_id');
    }

    /**
     * 商品相册关联
     */
    public function galleries(): HasMany
    {
        return $this->hasMany(GoodsGallery::class, 'goods_id');
    }

    /**
     * 商品类型关联
     */
    public function goodsType(): BelongsTo
    {
        return $this->belongsTo(GoodsType::class, 'goods_type');
    }

    /**
     * 会员价格关联
     */
    public function memberPrices(): HasMany
    {
        return $this->hasMany(GoodsMemberPrice::class, 'goods_id');
    }

    /**
     * 关联商品关联
     */
    public function linkedGoods(): HasMany
    {
        return $this->hasMany(GoodsLinkGoods::class, 'goods_id');
    }

    /**
     * 商品规格关联
     */
    public function products(): HasMany
    {
        return $this->hasMany(GoodsProduct::class, 'goods_id');
    }

    /**
     * 虚拟卡关联
     */
    public function virtualCards(): HasMany
    {
        return $this->hasMany(GoodsVirtualCard::class, 'goods_id');
    }

    /**
     * 批发价关联
     */
    public function volumePrices(): HasMany
    {
        return $this->hasMany(GoodsVolumePrice::class, 'goods_id');
    }
}
