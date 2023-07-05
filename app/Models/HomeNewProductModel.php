<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeNewProductModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'home_new_products';

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
