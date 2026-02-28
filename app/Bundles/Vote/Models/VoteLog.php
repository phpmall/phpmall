<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Models;

use Illuminate\Database\Eloquent\Model;

class VoteLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vote_log';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'log_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vote_id',
        'ip_address',
        'vote_time',
        'created_time',
        'updated_time',
    ];
}
