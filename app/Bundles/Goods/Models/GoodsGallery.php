<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsGallery extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'goods_gallery';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'img_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'goods_id',
        'img_url',
        'img_desc',
        'thumb_url',
        'img_original',
        'created_time',
        'updated_time',
    ];
}
