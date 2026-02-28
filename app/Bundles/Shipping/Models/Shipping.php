<?php

declare(strict_types=1);

namespace App\Bundles\Shipping\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shipping';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'shipping_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shipping_code',
        'shipping_name',
        'shipping_desc',
        'insure',
        'support_cod',
        'enabled',
        'shipping_print',
        'print_bg',
        'config_label',
        'print_model',
        'shipping_order',
        'created_time',
        'updated_time',
    ];
}
