<?php

declare(strict_types=1);

namespace App\Modules\Merchant\Models;

use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'merchants';

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
        'name',
        'logo_url',
        'cover_url',
        'description',
        'contact_phone',
        'contact_name',
        'business_license_no',
        'business_license_url',
        'legal_person_name',
        'legal_person_id_card',
        'settlement_cycle',
        'settlement_rate',
        'status',
        'audit_status',
        'audit_remark',
        'frozen_reason',
        'frozen_until',
        'total_sales_amount',
        'total_order_count',
    ];
}
