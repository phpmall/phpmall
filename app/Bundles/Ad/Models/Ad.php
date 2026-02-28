<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ad';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'ad_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'position_id',
        'media_type',
        'ad_name',
        'ad_link',
        'ad_code',
        'start_time',
        'end_time',
        'link_man',
        'link_email',
        'link_phone',
        'click_count',
        'enabled',
        'created_time',
        'updated_time',
    ];
}
