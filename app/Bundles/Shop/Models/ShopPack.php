<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopPack extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_pack';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'pack_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pack_name',
        'pack_img',
        'pack_fee',
        'free_money',
        'pack_desc',
        'created_time',
        'updated_time',
    ];
}
