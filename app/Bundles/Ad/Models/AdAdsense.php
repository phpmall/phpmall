<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Models;

use Illuminate\Database\Eloquent\Model;

class AdAdsense extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ad_adsense';

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
        'from_ad',
        'referer',
        'clicks',
        'created_time',
        'updated_time',
    ];
}
