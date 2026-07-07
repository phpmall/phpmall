<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Notification\NotificationIndexRequest;
use App\Api\User\Responses\Notification\NotificationListResponse;
use App\Api\User\Responses\Notification\NotificationResponse;
use App\Modules\Notification\Services\NotificationService;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class NotificationController extends BaseController
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {
        parent::__construct();
    }

    #[OA\Get(path: '/notifications', security: [['bearerAuth' => []]], summary: '通知列表', tags: ['会员中心'])]
    #[OA\Parameter(name: 'is_read', in: 'query', description: '是否已读:0否,1是', schema: new OA\Schema(type: 'integer', nullable: true, enum: [0, 1]))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: NotificationListResponse::class))]
    public function index(NotificationIndexRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $result = $this->notificationService->getUserNotifications(
            $user->id,
            $request->validated()
        );

        $unreadCount = $this->notificationService->getUnreadCount($user->id);

        return $this->success([
            'items' => array_map(
                fn (array $item): array => $this->formatNotification($item),
                $result['items']
            ),
            'unread_count' => $unreadCount,
            'pagination' => $result['pagination'],
        ]);
    }

    #[OA\Get(path: '/notifications/{id}', security: [['bearerAuth' => []]], summary: '通知详情', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: NotificationResponse::class))]
    public function show(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);
        $notification = $this->notificationService->getUserNotification($user->id, $id);

        if ($notification === null) {
            return $this->error('通知不存在');
        }

        return $this->success($this->formatNotification($notification));
    }

    #[OA\Post(path: '/notifications/{id}/read', security: [['bearerAuth' => []]], summary: '标记通知已读', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function markRead(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);

        if (! $this->notificationService->markRead($user->id, $id)) {
            return $this->error('通知不存在');
        }

        return $this->success(['message' => '标记成功']);
    }

    #[OA\Post(path: '/notifications/read-all', security: [['bearerAuth' => []]], summary: '标记全部已读', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function markAllRead(Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $this->notificationService->markAllRead($user->id);

        return $this->success(['message' => '全部已读']);
    }

    #[OA\Delete(path: '/notifications/{id}', security: [['bearerAuth' => []]], summary: '删除通知', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);

        if (! $this->notificationService->removeUserNotification($user->id, $id)) {
            return $this->error('通知不存在');
        }

        return $this->success(['message' => '删除成功']);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatNotification(array $item): array
    {
        $typeMap = [
            1 => 'system',
            2 => 'promotion',
            3 => 'activity',
        ];

        return [
            'id' => (int) $item['id'],
            'type' => $typeMap[(int) $item['type']] ?? 'system',
            'title' => $item['title'],
            'content' => $item['content'],
            'is_read' => (int) ($item['is_read'] ?? 0),
            'target_type' => null,
            'target_id' => null,
            'created_at' => $item['created_at'],
            'read_at' => $item['read_at'] ?? null,
        ];
    }

    private function resolveUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401, '未登录');
        }

        return $user;
    }
}
