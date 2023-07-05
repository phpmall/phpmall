<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFullReductionModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_full_reductions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'product_id',
        'full_price',
        'reduce_price',
    ];
}
