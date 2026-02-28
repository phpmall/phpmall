<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use App\Bundles\Shop\Models\ShopRegion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_address';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'address_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'address_name',
        'user_id',
        'consignee',
        'email',
        'country',
        'province',
        'city',
        'district',
        'address',
        'zipcode',
        'tel',
        'mobile',
        'sign_building',
        'best_time',
        'created_time',
        'updated_time',
    ];

    /**
     * 用户关联
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 国家关联
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(ShopRegion::class, 'country');
    }

    /**
     * 省份关联
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(ShopRegion::class, 'province');
    }

    /**
     * 城市关联
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(ShopRegion::class, 'city');
    }

    /**
     * 区县关联
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(ShopRegion::class, 'district');
    }
}
