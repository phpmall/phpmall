<?php

declare(strict_types=1);

namespace App\Bundles\Supplier\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'supplier';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'suppliers_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'suppliers_name',
        'suppliers_desc',
        'is_check',
        'created_time',
        'updated_time',
    ];
}
