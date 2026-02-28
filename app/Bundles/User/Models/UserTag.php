<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserTag extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_tag';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'tag_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'goods_id',
        'tag_words',
        'created_time',
        'updated_time',
    ];
}
