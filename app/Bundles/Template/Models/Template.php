<?php

declare(strict_types=1);

namespace App\Bundles\Template\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'template';

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
        'filename',
        'region',
        'library',
        'sort_order',
        'id_value',
        'number',
        'type',
        'theme',
        'remarks',
        'created_time',
        'updated_time',
    ];
}
