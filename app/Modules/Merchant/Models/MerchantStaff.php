<?php

declare(strict_types=1);

namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;

class MerchantStaff extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchant_staffs';

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
        'merchant_id',
        'username',
        'password_hash',
        'real_name',
        'phone',
        'status',
        'last_login_at',
    ];
}
