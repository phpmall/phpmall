<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Message\MessageIndexRequest;
use App\Api\User\Responses\Message\MessageListResponse;
use App\Api\User\Responses\Message\MessageResponse;
use App\Api\User\Responses\Message\MessageUnreadCountResponse;
use App\Modules\Message\Services\MessageService;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MessageController extends BaseController
{
    public function __construct(
        private readonly MessageService $messageService,
    ) {
        parent::__construct();
    }

    #[OA\Get(path: '/messages', security: [['bearerAuth' => []]], summary: '消息列表', tags: ['会员中心'])]
    #[OA\Parameter(name: 'is_read', in: 'query', description: '是否已读:0否,1是', schema: new OA\Schema(type: 'integer', nullable: true, enum: [0, 1]))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MessageListResponse::class))]
    public function index(MessageIndexRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $result = $this->messageService->getUserMessages(
            $user->id,
            $request->validated()
        );

        $unreadCount = $this->messageService->getUnreadCount($user->id);

        return $this->success([
            'items' => array_map(
                fn (array $item): array => $this->formatMessage($item),
                $result['items']
            ),
            'unread_count' => $unreadCount['total'],
            'pagination' => $result['pagination'],
        ]);
    }

    #[OA\Get(path: '/messages/unread-count', security: [['bearerAuth' => []]], summary: '未读消息数', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MessageUnreadCountResponse::class))]
    public function unreadCount(Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $counts = $this->messageService->getUnreadCount($user->id);

        return $this->success([
            'total' => $counts['total'],
            'system' => $counts['system'],
            'order' => $counts['order'],
            'activity' => $counts['activity'],
            'promotion' => $counts['promotion'],
        ]);
    }

    #[OA\Post(path: '/messages/{id}/read', security: [['bearerAuth' => []]], summary: '标记消息已读', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MessageResponse::class))]
    public function markRead(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);
        $message = $this->messageService->getUserMessage($user->id, $id);

        if ($message === null) {
            return $this->error('消息不存在');
        }

        $this->messageService->markRead($user->id, $id);
        $fresh = $this->messageService->getUserMessage($user->id, $id);

        return $this->success($this->formatMessage($fresh ?? []));
    }

    /**
     * @return array<string, mixed>
     */
    private function formatMessage(array $item): array
    {
        $extraData = null;
        if (! empty($item['extra_data'])) {
            $extraData = is_string($item['extra_data'])
                ? json_decode($item['extra_data'], true)
                : $item['extra_data'];
        }

        $typeMap = [
            1 => 'system',
            2 => 'order',
            3 => 'promotion',
            4 => 'activity',
        ];

        return [
            'id' => (int) $item['id'],
            'type' => $typeMap[(int) $item['type']] ?? 'system',
            'title' => $item['title'],
            'content' => $item['content'],
            'is_read' => (int) $item['is_read'],
            'target_type' => $extraData['target_type'] ?? null,
            'target_id' => $extraData['target_id'] ?? null,
            'created_at' => $item['created_at'],
            'read_at' => $item['read_at'],
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
