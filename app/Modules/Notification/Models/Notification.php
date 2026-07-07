<?php

declare(strict_types=1);

namespace App\Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

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
        'sender_id',
        'sender_type',
        'type',
        'title',
        'content',
        'priority',
        'publish_at',
        'expire_at',
        'status',
        'view_count',
    ];
}
