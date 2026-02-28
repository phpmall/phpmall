<?php

declare(strict_types=1);

namespace App\Bundles\Email\Models;

use Illuminate\Database\Eloquent\Model;

class EmailSend extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_send';

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
        'template_id',
        'email_content',
        'error',
        'pri',
        'last_send',
        'created_time',
        'updated_time',
    ];
}
