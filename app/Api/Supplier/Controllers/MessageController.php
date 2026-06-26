<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Requests\Message\MarkReadRequest;
use App\Api\Supplier\Responses\Message\MessageListResponse;
use App\Api\Supplier\Responses\Message\MessageMarkReadResponse;
use App\Api\Supplier\Responses\Message\MessageResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MessageController extends BaseController
{
    #[OA\Get(path: '/messages', summary: '消息列表', tags: ['供应商中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MessageListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/messages/{id}', summary: '消息详情', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MessageResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/messages/{id}/read', security: [['bearerAuth' => []]], summary: '标记消息已读', tags: ['供应商中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: MarkReadRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MessageMarkReadResponse::class))]
    public function markRead(MarkReadRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }
}
