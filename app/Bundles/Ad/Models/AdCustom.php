<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Models;

use Illuminate\Database\Eloquent\Model;

class AdCustom extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ad_custom';

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
        'ad_type',
        'ad_name',
        'add_time',
        'content',
        'url',
        'ad_status',
        'created_time',
        'updated_time',
    ];
}
