<?php

namespace App\Modules\Auth\Models;

use Carbon\Carbon;
use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $display_name
 * @property string|null $description
 * @property int $status
 * @property int $sort
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Role extends Model
{
    /** @use HasFactory<RoleFactory> */
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'status',
        'sort',
    ];

    protected $casts = [
        'status' => 'integer',
        'sort' => 'integer',
    ];

    /**
     * @return BelongsToMany<Permission, $this>
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'role_id', 'permission_id')
            ->withTimestamps();
    }
}
