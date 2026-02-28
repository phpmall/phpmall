<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserExtendFields extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_extend_fields';

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
        'reg_field_name',
        'dis_order',
        'display',
        'type',
        'is_need',
        'created_time',
        'updated_time',
    ];
}
