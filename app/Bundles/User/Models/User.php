<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Bundles\Order\Models\OrderInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'user_name',
        'password',
        'question',
        'answer',
        'sex',
        'birthday',
        'user_money',
        'frozen_money',
        'pay_points',
        'rank_points',
        'address_id',
        'reg_time',
        'last_login',
        'last_time',
        'last_ip',
        'visit_count',
        'user_rank',
        'is_special',
        'ec_salt',
        'salt',
        'parent_id',
        'flag',
        'alias',
        'msn',
        'qq',
        'office_phone',
        'home_phone',
        'mobile_phone',
        'is_validated',
        'credit_line',
        'passwd_question',
        'passwd_answer',
        'remember_token',
        'created_time',
        'updated_time',
    ];

    /**
     * 用户地址关联
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class, 'user_id');
    }

    /**
     * 默认地址关联
     */
    public function defaultAddress(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    /**
     * 购物车关联
     */
    public function carts(): HasMany
    {
        return $this->hasMany(UserCart::class, 'user_id');
    }

    /**
     * 订单关联
     */
    public function orders(): HasMany
    {
        return $this->hasMany(OrderInfo::class, 'user_id');
    }

    /**
     * 用户等级关联
     */
    public function rank(): BelongsTo
    {
        return $this->belongsTo(UserRank::class, 'user_rank');
    }

    /**
     * 用户收藏关联
     */
    public function collects(): HasMany
    {
        return $this->hasMany(UserCollect::class, 'user_id');
    }

    /**
     * 用户红包关联
     */
    public function bonuses(): HasMany
    {
        return $this->hasMany(UserBonus::class, 'user_id');
    }

    /**
     * 账户余额记录关联
     */
    public function accountLogs(): HasMany
    {
        return $this->hasMany(UserAccountLog::class, 'user_id');
    }

    /**
     * 推荐用户（下线）关联
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    /**
     * 上级用户关联
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
}
