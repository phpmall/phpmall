<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class MessageController extends BaseController
{
    #[OA\Get(path: '/messages', summary: '获取消息列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/messages/{id}', summary: '获取消息详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '消息ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/messages/{id}/read', summary: '标记消息已读', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '消息ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function markRead(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/messages/batch/read', summary: '批量标记已读', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchRead(Request $request): JsonResponse
    {
        return $this->success();
    }
}
