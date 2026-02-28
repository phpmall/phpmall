<?php

declare(strict_types=1);

namespace App\Bundles\Admin\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Bundles\Supplier\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_user';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'email',
        'password',
        'ec_salt',
        'add_time',
        'last_login',
        'last_ip',
        'action_list',
        'nav_list',
        'lang_type',
        'agency_id',
        'suppliers_id',
        'todolist',
        'role_id',
        'remember_token',
        'created_time',
        'updated_time',
    ];

    /**
     * 管理员角色关联
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(AdminRole::class, 'role_id');
    }

    /**
     * 所属供货商关联
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'suppliers_id');
    }

    /**
     * 操作日志关联
     */
    public function logs(): HasMany
    {
        return $this->hasMany(AdminLog::class, 'user_id');
    }

    /**
     * 站内信关联
     */
    public function messages(): HasMany
    {
        return $this->hasMany(AdminMessage::class, 'receiver_id');
    }
}
