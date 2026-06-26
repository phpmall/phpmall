<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ComplaintController extends BaseController
{
    #[OA\Get(path: '/complaints', summary: '获取投诉列表', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/complaints/{id}', summary: '获取投诉详情', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '投诉ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/complaints/{id}/respond', summary: '回应投诉', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '投诉ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function respond(Request $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/complaints/{id}/appeal', summary: '申诉投诉', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Parameter(name: 'id', description: '投诉ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function appeal(Request $request, int $id): JsonResponse
    {
        return $this->success();
    }
}
