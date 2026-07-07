<?php

declare(strict_types=1);

namespace App\Modules\Notification\Services;

use Illuminate\Support\Facades\DB;

class NoticeService
{
    /**
     * 获取最新的平台公告
     */
    public function getLatest(int $limit = 5): array
    {
        $rows = DB::table('notifications')
            ->where('status', 1)
            ->where('type', 1)
            ->where(function ($query): void {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->orderByDesc('publish_at')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn ($row): array => (array) $row)
            ->all();

        return array_map(fn (array $row): array => $this->formatNotice($row), $rows);
    }

    private function formatNotice(array $row): array
    {
        return [
            'id' => (int) $row['id'],
            'title' => $row['title'],
            'content' => $row['content'],
            'priority' => (int) $row['priority'],
            'publishAt' => $row['publish_at'] ?? $row['created_at'],
            'createdAt' => $row['created_at'],
        ];
    }
}
