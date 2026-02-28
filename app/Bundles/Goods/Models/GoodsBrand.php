<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GoodsBrand extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goods_brand';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'brand_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand_name',
        'brand_logo',
        'brand_desc',
        'site_url',
        'sort_order',
        'is_show',
        'created_time',
        'updated_time',
    ];

    /**
     * 商品关联
     */
    public function goods(): HasMany
    {
        return $this->hasMany(Goods::class, 'brand_id');
    }
}
