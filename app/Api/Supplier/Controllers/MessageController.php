<?php

declare(strict_types=1);

namespace App\Api\Supplier\Controllers;

use App\Api\Supplier\Requests\Message\MarkReadRequest;
use App\Api\Supplier\Requests\Message\MessageIndexRequest;
use App\Api\Supplier\Responses\Message\MessageListResponse;
use App\Api\Supplier\Responses\Message\MessageMarkReadResponse;
use App\Api\Supplier\Responses\Message\MessageResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class MessageController extends BaseController
{
    #[OA\Get(path: '/messages', summary: '消息列表', security: [['bearerAuth' => []]], tags: ['供应商中心'])]
    #[OA\Parameter(name: 'is_read', description: '是否已读', in: 'query', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', description: '每页数量', in: 'query', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: MessageListResponse::class))]
    public function index(MessageIndexRequest $request): JsonResponse
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
