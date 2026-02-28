<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Models;

use Illuminate\Database\Eloquent\Model;

class VoteOption extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vote_option';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'option_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vote_id',
        'option_name',
        'option_count',
        'option_order',
        'created_time',
        'updated_time',
    ];
}
