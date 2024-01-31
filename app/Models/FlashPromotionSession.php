<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashPromotionSession extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'flash_promotion_sessions';

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
