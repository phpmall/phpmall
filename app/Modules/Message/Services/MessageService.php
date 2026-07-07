<?php

declare(strict_types=1);

namespace App\Modules\Message\Services;

use App\Modules\Message\Repositories\MessageRepository;
use Illuminate\Support\Facades\DB;
use Juling\Foundation\Contracts\ServiceInterface;
use Juling\Foundation\Services\CommonService;

class MessageService extends CommonService implements ServiceInterface
{
    public function __construct(
        private readonly MessageRepository $repository,
    ) {}

    public function getRepository(): MessageRepository
    {
        return $this->repository;
    }

    /**
     * 获取用户消息分页列表
     */
    public function getUserMessages(int $userId, array $params = []): array
    {
        $page = (int) ($params['page'] ?? 1);
        $perPage = (int) ($params['per_page'] ?? 20);
        $isRead = $params['is_read'] ?? null;

        $query = DB::table('messages')
            ->where('user_id', $userId)
            ->where('status', 1)
            ->orderByDesc('created_at');

        if ($isRead !== null) {
            $query->where('is_read', (int) $isRead);
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
     * 获取用户未读消息数（按类型分组）
     */
    public function getUnreadCount(int $userId): array
    {
        $rows = DB::table('messages')
            ->select('type', DB::raw('COUNT(*) as count'))
            ->where('user_id', $userId)
            ->where('status', 1)
            ->where('is_read', 0)
            ->groupBy('type')
            ->get();

        $counts = [
            1 => 0, // system
            2 => 0, // order
            3 => 0, // promotion
            4 => 0, // activity
        ];

        foreach ($rows as $row) {
            $counts[(int) $row->type] = (int) $row->count;
        }

        return [
            'total' => array_sum($counts),
            'system' => $counts[1],
            'order' => $counts[2],
            'promotion' => $counts[3],
            'activity' => $counts[4],
        ];
    }

    /**
     * 标记消息已读
     */
    public function markRead(int $userId, int $messageId): bool
    {
        $affected = DB::table('messages')
            ->where('id', $messageId)
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->update([
                'is_read' => 1,
                'read_at' => now(),
            ]);

        return $affected > 0;
    }

    /**
     * 获取单条消息
     */
    public function getUserMessage(int $userId, int $messageId): ?array
    {
        $row = DB::table('messages')
            ->where('id', $messageId)
            ->where('user_id', $userId)
            ->where('status', 1)
            ->first();

        return $row === null ? null : (array) $row;
    }
}
