<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class OrderController extends BaseController
{
    #[OA\Get(path: '/orders', summary: 'Order Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/orders', security: [['bearerAuth' => []]], summary: 'Order Controller store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent)]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/orders/{id}', summary: 'Order Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/orders/{id}/cancel', security: [['bearerAuth' => []]], summary: 'Order Controller cancel', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function cancel(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/orders/{id}/confirm', security: [['bearerAuth' => []]], summary: 'Order Controller confirm', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function confirm(): JsonResponse
    {
        return $this->success();
    }
}
