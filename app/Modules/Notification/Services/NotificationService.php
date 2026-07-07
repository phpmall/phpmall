<?php

declare(strict_types=1);

namespace App\Modules\Notification\Services;

use App\Modules\Notification\Repositories\NotificationRepository;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class NotificationService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly NotificationRepository $repository,
    ) {}

    public function getRepository(): NotificationRepository
    {
        return $this->repository;
    }

    /**
     * 获取用户可见的通知分页列表
     */
    public function getUserNotifications(int $userId, array $params = []): array
    {
        $page = (int) ($params['page'] ?? 1);
        $perPage = (int) ($params['per_page'] ?? 20);
        $isRead = $params['is_read'] ?? null;

        $query = DB::table('notifications')
            ->where('notifications.status', 1)
            ->where(function ($query): void {
                $query->whereNull('notifications.publish_at')
                    ->orWhere('notifications.publish_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('notifications.expire_at')
                    ->orWhere('notifications.expire_at', '>=', now());
            })
            ->orderByDesc('notifications.publish_at')
            ->orderByDesc('notifications.created_at');

        $readSubQuery = DB::table('user_notifications')
            ->select('notification_id')
            ->where('user_id', $userId)
            ->whereNotNull('read_at');

        $query->leftJoinSub($readSubQuery, 'read_states', function ($join): void {
            $join->on('notifications.id', '=', 'read_states.notification_id');
        });

        $query->select(
            'notifications.*',
            DB::raw('CASE WHEN read_states.notification_id IS NOT NULL THEN 1 ELSE 0 END as is_read')
        );

        if ($isRead !== null) {
            if ((int) $isRead === 1) {
                $query->whereNotNull('read_states.notification_id');
            } else {
                $query->whereNull('read_states.notification_id');
            }
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
        $items = collect($paginator->items())->map(fn (object $row): array => (array) $row)->all();

        return [
            'items' => $items,
            'pagination' => [
                'page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'total_pages' => $paginator->lastPage(),
                'has_next' => $paginator->hasMorePages(),
                'has_prev' => $paginator->onFirstPage() === false,
            ],
        ];
    }

    /**
     * 获取单条通知及用户阅读状态
     */
    public function getUserNotification(int $userId, int $notificationId): ?array
    {
        $row = DB::table('notifications')
            ->where('notifications.id', $notificationId)
            ->where('notifications.status', 1)
            ->where(function ($query): void {
                $query->whereNull('notifications.publish_at')
                    ->orWhere('notifications.publish_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('notifications.expire_at')
                    ->orWhere('notifications.expire_at', '>=', now());
            })
            ->leftJoin('user_notifications', function ($join) use ($userId): void {
                $join->on('notifications.id', '=', 'user_notifications.notification_id')
                    ->where('user_notifications.user_id', $userId);
            })
            ->select(
                'notifications.*',
                DB::raw('CASE WHEN user_notifications.read_at IS NOT NULL THEN 1 ELSE 0 END as is_read'),
                'user_notifications.read_at'
            )
            ->first();

        return $row === null ? null : (array) $row;
    }

    /**
     * 标记通知已读
     */
    public function markRead(int $userId, int $notificationId): bool
    {
        $exists = DB::table('notifications')
            ->where('id', $notificationId)
            ->where('status', 1)
            ->exists();

        if (! $exists) {
            return false;
        }

        DB::table('user_notifications')->updateOrInsert(
            [
                'user_id' => $userId,
                'notification_id' => $notificationId,
            ],
            [
                'read_at' => now(),
                'updated_at' => now(),
            ]
        );

        return true;
    }

    /**
     * 标记所有通知已读
     */
    public function markAllRead(int $userId): void
    {
        $notificationIds = DB::table('notifications')
            ->where('status', 1)
            ->where(function ($query): void {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('expire_at')
                    ->orWhere('expire_at', '>=', now());
            })
            ->pluck('id');

        $existingIds = DB::table('user_notifications')
            ->where('user_id', $userId)
            ->whereIn('notification_id', $notificationIds)
            ->pluck('notification_id')
            ->all();

        $now = now();
        $newIds = array_diff($notificationIds->all(), $existingIds);
        $inserts = array_map(fn (int $id): array => [
            'user_id' => $userId,
            'notification_id' => $id,
            'read_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ], $newIds);

        if (! empty($inserts)) {
            DB::table('user_notifications')->insert($inserts);
        }

        DB::table('user_notifications')
            ->where('user_id', $userId)
            ->whereIn('notification_id', $existingIds)
            ->whereNull('read_at')
            ->update(['read_at' => $now, 'updated_at' => $now]);
    }

    /**
     * 删除用户对某条通知的可见记录
     */
    public function removeUserNotification(int $userId, int $notificationId): bool
    {
        $affected = DB::table('user_notifications')
            ->where('user_id', $userId)
            ->where('notification_id', $notificationId)
            ->delete();

        return $affected > 0;
    }

    /**
     * 获取用户未读通知数
     */
    public function getUnreadCount(int $userId): int
    {
        $total = DB::table('notifications')
            ->where('status', 1)
            ->where(function ($query): void {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->where(function ($query): void {
                $query->whereNull('expire_at')
                    ->orWhere('expire_at', '>=', now());
            })
            ->count();

        $readCount = DB::table('user_notifications')
            ->where('user_id', $userId)
            ->whereNotNull('read_at')
            ->count();

        return max(0, $total - $readCount);
    }
}
