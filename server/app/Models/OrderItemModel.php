<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_items';

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
