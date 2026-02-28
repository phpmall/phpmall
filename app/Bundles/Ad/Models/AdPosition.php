<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Models;

use Illuminate\Database\Eloquent\Model;

class AdPosition extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ad_position';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'position_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'position_name',
        'ad_width',
        'ad_height',
        'position_desc',
        'position_style',
        'created_time',
        'updated_time',
    ];
}
