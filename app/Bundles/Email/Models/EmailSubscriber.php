<?php

declare(strict_types=1);

namespace App\Bundles\Email\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSubscriber extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_subscriber';

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
        'email',
        'stat',
        'hash',
        'created_time',
        'updated_time',
    ];
}
