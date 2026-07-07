<?php

declare(strict_types=1);

namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shops';

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
        'name',
        'logo_url',
        'cover_url',
        'description',
        'contact_phone',
        'contact_name',
        'status',
        'audit_status',
        'audit_remark',
        'frozen_reason',
        'frozen_until',
        'total_sales_amount',
        'total_order_count',
    ];
}
