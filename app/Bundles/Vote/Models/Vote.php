<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vote';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'vote_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vote_name',
        'start_time',
        'end_time',
        'can_multi',
        'vote_count',
        'created_time',
        'updated_time',
    ];
}
