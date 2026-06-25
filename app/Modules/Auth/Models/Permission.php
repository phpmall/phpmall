<?php

namespace App\Modules\Auth\Models;

use Carbon\Carbon;
use Database\Factories\PermissionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $description
 * @property int $parent_id
 * @property string $type
 * @property string|null $route
 * @property string|null $icon
 * @property int $sort
 * @property int $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Permission extends Model
{
    /** @use HasFactory<PermissionFactory> */
    use HasFactory;

    protected $table = 'permissions';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'parent_id',
        'type',
        'route',
        'icon',
        'sort',
        'status',
    ];

    protected $casts = [
        'parent_id' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
    ];

    /**
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id')
            ->withTimestamps();
    }

    /**
     * @phpstan-ignore-next-line
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * @return BelongsTo<self, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }
}
