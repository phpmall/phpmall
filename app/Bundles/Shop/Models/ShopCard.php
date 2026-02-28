<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCard extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_card';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'card_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'card_name',
        'card_img',
        'card_fee',
        'free_money',
        'card_desc',
        'created_time',
        'updated_time',
    ];
}
