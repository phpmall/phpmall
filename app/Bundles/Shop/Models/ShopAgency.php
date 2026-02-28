<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ShopAgency extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shop_agency';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'agency_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agency_name',
        'agency_desc',
        'created_time',
        'updated_time',
    ];
}
