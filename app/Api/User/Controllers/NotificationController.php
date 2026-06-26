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
