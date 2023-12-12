<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'access_time',
        'visit_times',
        'ip_address',
        'system',
        'browser',
        'language',
        'area',
        'referer_domain',
        'referer_path',
        'access_url',
        'created_at',
        'updated_at',
    ];
}
