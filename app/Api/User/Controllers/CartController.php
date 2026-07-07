<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Cart\CartBatchStoreRequest;
use App\Api\User\Requests\Cart\CartIndexRequest;
use App\Api\User\Requests\Cart\CartStoreRequest;
use App\Api\User\Requests\Cart\CartUpdateRequest;
use App\Api\User\Responses\Cart\CartListResponse;
use App\Modules\Cart\Services\CartService;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;

class CartController extends BaseController
{
    public function __construct(
        private readonly CartService $cartService,
    ) {
        parent::__construct();
    }

    #[OA\Get(path: '/cart', security: [['bearerAuth' => []]], summary: '购物车列表', tags: ['会员中心'])]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CartListResponse::class))]
    public function index(CartIndexRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $page = (int) $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 20);

        $listResponse = $this->cartService->getCartList($user->id, $page, $perPage);

        return response()->json([
            'code' => 0,
            'message' => 'ok',
            'data' => $listResponse,
        ]);
    }

    #[OA\Post(path: '/cart', security: [['bearerAuth' => []]], summary: '添加购物车商品', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CartStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function store(CartStoreRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $data = $request->validated();

        try {
            $item = $this->cartService->addItem($user->id, (int) $data['sku_id'], (int) $data['quantity']);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return response()->json([
            'code' => 0,
            'message' => '添加成功',
            'data' => $item,
        ]);
    }

    #[OA\Put(path: '/cart/{id}', security: [['bearerAuth' => []]], summary: '更新购物车商品', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: '购物车ID', in: 'path', required: true)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CartUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(CartUpdateRequest $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);
        $data = $request->validated();

        try {
            $item = $this->cartService->updateItem(
                $user->id,
                $id,
                (int) $data['quantity'],
                isset($data['is_selected']) ? (int) $data['is_selected'] : null
            );
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return response()->json([
            'code' => 0,
            'message' => '更新成功',
            'data' => $item,
        ]);
    }

    #[OA\Delete(path: '/cart/{id}', security: [['bearerAuth' => []]], summary: '删除购物车商品', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: '购物车ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $this->resolveUser($request);

        $this->cartService->deleteItem($user->id, $id);

        return response()->json([
            'code' => 0,
            'message' => '删除成功',
        ]);
    }

    #[OA\Post(path: '/cart/clear', security: [['bearerAuth' => []]], summary: '清空购物车', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function clear(Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        $this->cartService->clear($user->id);

        return response()->json([
            'code' => 0,
            'message' => '清空成功',
        ]);
    }

    #[OA\Post(path: '/cart/batch', security: [['bearerAuth' => []]], summary: '批量添加购物车商品', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CartBatchStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function batchStore(CartBatchStoreRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $data = $request->validated();

        try {
            $items = $this->cartService->batchAddItems($user->id, $data['items']);
        } catch (BusinessException $e) {
            return $this->error($e->getMessage());
        }

        return response()->json([
            'code' => 0,
            'message' => '批量添加成功',
            'data' => $items,
        ]);
    }

    private function resolveUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401, '未登录');
        }

        return $user;
    }
}
