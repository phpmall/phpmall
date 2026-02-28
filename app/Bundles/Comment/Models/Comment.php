<?php

declare(strict_types=1);

namespace App\Bundles\Comment\Models;

use App\Bundles\User\Models\User;
use App\Bundles\Goods\Models\Goods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comment';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'comment_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'comment_type',
        'id_value',
        'email',
        'user_name',
        'content',
        'comment_rank',
        'add_time',
        'ip_address',
        'status',
        'parent_id',
        'user_id',
        'created_time',
        'updated_time',
    ];

    /**
     * 用户关联
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * 商品关联（根据 comment_type 判断）
     */
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class, 'id_value');
    }

    /**
     * 父评论关联
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * 子评论关联
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
