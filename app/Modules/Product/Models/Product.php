<?php

declare(strict_types=1);

namespace App\Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'merchant_id',
        'category_id',
        'title',
        'subtitle',
        'description',
        'main_image',
        'images',
        'status',
        'audit_status',
        'audit_remark',
        'min_price',
        'max_price',
        'cost_price',
        'sales_count',
        'stock_type',
        'total_stock',
        'weight',
        'freight_template_id',
        'attributes',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'is_hot',
        'is_new',
        'is_recommend',
        'sort_order',
    ];
}
