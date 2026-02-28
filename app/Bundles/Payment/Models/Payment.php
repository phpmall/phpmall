<?php

declare(strict_types=1);

namespace App\Bundles\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'pay_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pay_code',
        'pay_name',
        'pay_fee',
        'pay_desc',
        'pay_order',
        'pay_config',
        'enabled',
        'is_cod',
        'is_online',
        'created_time',
        'updated_time',
    ];
}
