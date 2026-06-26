<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Notification\NotificationIndexRequest;
use App\Api\User\Responses\Notification\NotificationListResponse;
use App\Api\User\Responses\Notification\NotificationResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class NotificationController extends BaseController
{
    #[OA\Get(path: '/notifications', security: [['bearerAuth' => []]], summary: 'Notification Controller index', tags: ['会员中心'])]
    #[OA\Parameter(name: 'is_read', in: 'query', description: '是否已读:0否,1是', schema: new OA\Schema(type: 'integer', nullable: true, enum: [0, 1]))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: NotificationListResponse::class))]
    public function index(NotificationIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/notifications/{id}', security: [['bearerAuth' => []]], summary: 'Notification Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: NotificationResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/notifications/{id}/read', security: [['bearerAuth' => []]], summary: 'Notification Controller mark Read', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function markRead(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/notifications/read-all', security: [['bearerAuth' => []]], summary: 'Notification Controller mark All Read', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function markAllRead(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/notifications/{id}', security: [['bearerAuth' => []]], summary: 'Notification Controller destroy', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }
}
