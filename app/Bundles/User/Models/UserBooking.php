<?php

declare(strict_types=1);

namespace App\Bundles\User\Models;

use Illuminate\Database\Eloquent\Model;

class UserBooking extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_booking';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'rec_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'email',
        'link_man',
        'tel',
        'goods_id',
        'goods_desc',
        'goods_number',
        'booking_time',
        'is_dispose',
        'dispose_user',
        'dispose_time',
        'dispose_note',
        'created_time',
        'updated_time',
    ];
}
