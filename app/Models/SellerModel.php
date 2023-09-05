<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sellers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user_id',
        'company_name',
        'company_address',
        'legal_person',
        'business_license',
        'tax_registration',
        'opening_bank',
        'bank_account',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
