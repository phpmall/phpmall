<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeModel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_attributes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'product_type_id',
        'name',
        'select_type',
        'input_type',
        'input_list',
        'sort',
        'filter_type',
        'search_type',
        'related_status',
        'hand_add_status',
        'type',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
