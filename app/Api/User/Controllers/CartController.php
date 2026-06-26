<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Cart\CartBatchStoreRequest;
use App\Api\User\Requests\Cart\CartStoreRequest;
use App\Api\User\Requests\Cart\CartUpdateRequest;
use App\Api\User\Responses\Cart\CartListResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class CartController extends BaseController
{
    #[OA\Get(path: '/cart', security: [['bearerAuth' => []]], summary: 'Cart Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CartListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/cart', security: [['bearerAuth' => []]], summary: 'Cart Controller store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CartStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CartListResponse::class))]
    public function store(CartStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/cart/{id}', security: [['bearerAuth' => []]], summary: 'Cart Controller update', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CartUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CartListResponse::class))]
    public function update(CartUpdateRequest $request, int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Delete(path: '/cart/{id}', security: [['bearerAuth' => []]], summary: 'Cart Controller destroy', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CartListResponse::class))]
    public function destroy(int $id): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/cart/clear', security: [['bearerAuth' => []]], summary: 'Cart Controller clear', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CartListResponse::class))]
    public function clear(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/cart/batch', security: [['bearerAuth' => []]], summary: 'Cart Controller batch Store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CartBatchStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CartListResponse::class))]
    public function batchStore(CartBatchStoreRequest $request): JsonResponse
    {
        return $this->success();
    }
}
