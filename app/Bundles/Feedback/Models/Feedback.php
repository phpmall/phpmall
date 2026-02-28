<?php

declare(strict_types=1);

namespace App\Bundles\Feedback\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feedback';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'msg_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'user_id',
        'user_name',
        'user_email',
        'msg_title',
        'msg_type',
        'msg_status',
        'msg_content',
        'msg_time',
        'message_img',
        'order_id',
        'msg_area',
        'created_time',
        'updated_time',
    ];
}
