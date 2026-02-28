<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopFriendLink extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_friend_link';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'link_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'link_name',
        'link_url',
        'link_logo',
        'show_order',
        'created_time',
        'updated_time',
    ];
}
