<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnedOrderModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'returned_orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
    ];
}
