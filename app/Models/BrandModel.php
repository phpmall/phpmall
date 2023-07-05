<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'brands';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'first_letter',
        'logo',
        'big_pic',
        'brand_story',
        'factory_status',
        'show_status',
        'product_count',
        'product_comment_count',
        'sort',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
