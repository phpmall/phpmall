<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Message\MessageIndexRequest;
use App\Api\User\Responses\Message\MessageListResponse;
use App\Api\User\Responses\Message\MessageResponse;
use App\Api\User\Responses\Message\MessageUnreadCountResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MessageController extends BaseController
{
    #[OA\Get(path: '/messages', security: [['bearerAuth' => []]], summary: 'Message Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MessageListResponse::class))]
    public function index(MessageIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/messages/unread-count', security: [['bearerAuth' => []]], summary: 'Message Controller unread Count', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MessageUnreadCountResponse::class))]
    public function unreadCount(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/messages/{id}/read', security: [['bearerAuth' => []]], summary: 'Message Controller mark Read', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MessageResponse::class))]
    public function markRead(int $id): JsonResponse
    {
        return $this->success();
    }
}
