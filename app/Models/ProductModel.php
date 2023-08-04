<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'seller_id',
        'shop_id',
        'category_id',
        'category_name',
        'brand_id',
        'brand_name',
        'freight_template_id',
        'product_type_id',
        'product_sn',
        'name',
        'pic',
        'original_price',
        'price',
        'promotion_type',
        'promotion_price',
        'promotion_start_time',
        'promotion_end_time',
        'promotion_per_limit',
        'gift_growth',
        'gift_point',
        'use_point_limit',
        'sale',
        'stock',
        'low_stock',
        'unit',
        'weight',
        'preview_status',
        'service_ids',
        'sub_title',
        'description',
        'keywords',
        'note',
        'album_pics',
        'detail_title',
        'detail_desc',
        'detail_html',
        'detail_mobile_html',
        'delete_status',
        'publish_status',
        'new_status',
        'recommend_status',
        'verify_status',
        'sort',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
