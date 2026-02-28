<?php

declare(strict_types=1);

namespace App\Bundles\Email\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'email_template';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'template_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'template_code',
        'is_html',
        'template_subject',
        'template_content',
        'last_modify',
        'last_send',
        'created_time',
        'updated_time',
    ];
}
