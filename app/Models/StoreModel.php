<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'seller_id',
        'store_logo',
        'store_introduce',
        'store_background',
        'store_category',
        'store_rating',
        'store_status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
