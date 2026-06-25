<?php

namespace App\Modules\User\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $contact_name
 * @property string $contact_phone
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $detail
 * @property string|null $zip_code
 * @property int $is_default
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Address extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'contact_name',
        'contact_phone',
        'province',
        'city',
        'district',
        'detail',
        'zip_code',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'integer',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
