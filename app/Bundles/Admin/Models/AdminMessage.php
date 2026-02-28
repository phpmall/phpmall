<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class AdminMessage extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_message';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'message_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'sent_time',
        'read_time',
        'readed',
        'deleted',
        'title',
        'message',
        'created_time',
        'updated_time',
    ];
}
