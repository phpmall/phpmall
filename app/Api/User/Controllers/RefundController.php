<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Refund\RefundStoreRequest;
use App\Api\User\Responses\Refund\RefundListResponse;
use App\Api\User\Responses\Refund\RefundResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class RefundController extends BaseController
{
    #[OA\Get(path: '/refunds', security: [['bearerAuth' => []]], summary: 'Refund Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RefundListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/refunds', security: [['bearerAuth' => []]], summary: 'Refund Controller store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RefundStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RefundResponse::class))]
    public function store(RefundStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/refunds/{id}', security: [['bearerAuth' => []]], summary: 'Refund Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RefundResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/refunds/{id}/cancel', security: [['bearerAuth' => []]], summary: 'Refund Controller cancel', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function cancel(): JsonResponse
    {
        return $this->success();
    }
}
