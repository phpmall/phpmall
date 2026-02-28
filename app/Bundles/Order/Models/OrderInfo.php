<?php

declare(strict_types=1);

namespace App\Bundles\Order\Models;

use App\Bundles\User\Models\User;
use App\Bundles\Goods\Models\GoodsBrand;
use App\Bundles\Goods\Models\GoodsCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderInfo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_info';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'order_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_sn',
        'user_id',
        'order_status',
        'shipping_status',
        'pay_status',
        'consignee',
        'country',
        'province',
        'city',
        'district',
        'address',
        'zipcode',
        'tel',
        'mobile',
        'email',
        'best_time',
        'sign_building',
        'postscript',
        'shipping_id',
        'shipping_name',
        'pay_id',
        'pay_name',
        'how_oos',
        'how_surplus',
        'pack_name',
        'card_name',
        'card_message',
        'inv_payee',
        'inv_content',
        'goods_amount',
        'shipping_fee',
        'insure_fee',
        'pay_fee',
        'pack_fee',
        'card_fee',
        'money_paid',
        'surplus',
        'integral',
        'integral_money',
        'bonus',
        'order_amount',
        'from_ad',
        'referer',
        'add_time',
        'confirm_time',
        'pay_time',
        'shipping_time',
        'pack_id',
        'card_id',
        'bonus_id',
        'invoice_no',
        'extension_code',
        'extension_id',
        'to_buyer',
        'pay_note',
        'agency_id',
        'inv_type',
        'tax',
        'is_separate',
        'parent_id',
        'discount',
        'created_time',
        'updated_time',
    ];

    /**
     * 订单商品关联
     */
    public function orderGoods(): HasMany
    {
        return $this->hasMany(OrderGoods::class, 'order_id');
    }

    /**
     * 用户关联
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 订单操作日志关联
     */
    public function actions(): HasMany
    {
        return $this->hasMany(OrderAction::class, 'order_id');
    }

    /**
     * 配送单关联
     */
    public function deliveryOrder(): HasOne
    {
        return $this->hasOne(OrderDeliveryOrder::class, 'order_id');
    }

    /**
     * 退货单关联
     */
    public function backOrder(): HasOne
    {
        return $this->hasOne(OrderBackOrder::class, 'order_id');
    }

    /**
     * 订单支付关联
     */
    public function payments(): HasMany
    {
        return $this->hasMany(OrderPay::class, 'order_id');
    }
}
