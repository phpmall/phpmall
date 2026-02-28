<?php

declare(strict_types=1);

namespace App\Bundles\Order\Models;

use App\Bundles\Goods\Models\Goods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderGoods extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_goods';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'rec_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'goods_id',
        'goods_name',
        'goods_sn',
        'product_id',
        'goods_number',
        'market_price',
        'goods_price',
        'goods_attr',
        'send_number',
        'is_real',
        'extension_code',
        'parent_id',
        'is_gift',
        'goods_attr_id',
        'created_time',
        'updated_time',
    ];

    /**
     * 订单关联
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderInfo::class, 'order_id');
    }

    /**
     * 商品关联
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'goods_id');
    }

    /**
     * 赠品关联（如果是赠品）
     */
    public function parentGoods(): BelongsTo
    {
        return $this->belongsTo(OrderGoods::class, 'parent_id', 'rec_id');
    }

    /**
     * 子商品关联（如果是主商品）
     */
    public function giftGoods(): HasMany
    {
        return $this->hasMany(OrderGoods::class, 'parent_id', 'rec_id');
    }
}
