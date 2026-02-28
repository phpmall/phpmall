<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopStats extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_stats';

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
        'access_time',
        'ip_address',
        'visit_times',
        'browser',
        'system',
        'language',
        'area',
        'referer_domain',
        'referer_path',
        'access_url',
        'created_time',
        'updated_time',
    ];
}
