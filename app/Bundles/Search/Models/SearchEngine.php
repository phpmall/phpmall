<?php

declare(strict_types=1);

namespace App\Bundles\Search\Models;

use Illuminate\Database\Eloquent\Model;

class SearchEngine extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'search_engine';

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
        'date',
        'search_engine',
        'count',
        'created_time',
        'updated_time',
    ];
}
