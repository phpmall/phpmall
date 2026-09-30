<?php

declare(strict_types=1);

namespace App\Api\Portal\Controllers;

use App\Api\Portal\Requests\Cart\AddToCartRequest;
use App\Api\Portal\Requests\Cart\RemoveCartItemsRequest;
use App\Api\Portal\Requests\Cart\SelectCartItemsRequest;
use App\Api\Portal\Requests\Cart\UpdateCartQuantityRequest;
use App\Api\Portal\Responses\Cart\CartListResponse;
use App\Services\Trade\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;

#[OA\Tag(name: '前台-购物车接口', description: 'PC 前台商城与移动多端统一购物车交互接口')]
class CartController extends BaseController
{
    public function __construct(
        protected readonly CartService $cartService,
    ) {}

    /**
     * 解析当前用户ID
     */
    protected function getUserId(Request $request): int
    {
        $userId = $request->user()?->id ?? $request->header('X-User-Id');
        if (! $userId) {
            throw new BusinessException('请先登录后再操作购物车');
        }

        return (int) $userId;
    }

    #[OA\Get(path: '/api/portal/cart', summary: '获取购物车列表及汇总', security: [['bearerAuth' => []]], tags: ['前台-购物车接口'])]
    #[OA\Response(
        response: 200,
        description: '成功返回购物车条目及勾选汇总',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(property: 'data', ref: CartListResponse::class),
            ]
        )
    )]
    public function index(Request $request): JsonResponse
    {
        $userId = $this->getUserId($request);
        $data = $this->cartService->getCartList($userId);

        return $this->success($data);
    }

    #[OA\Post(path: '/api/portal/cart', summary: '添加商品到购物车', security: [['bearerAuth' => []]], tags: ['前台-购物车接口'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AddToCartRequest::class))]
    #[OA\Response(
        response: 200,
        description: '加购成功',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(property: 'data', type: 'object'),
            ]
        )
    )]
    public function store(AddToCartRequest $request): JsonResponse
    {
        $userId = $this->getUserId($request);
        $skuId = (int) $request->input('sku_id');
        $quantity = (int) $request->input('quantity', 1);

        $cart = $this->cartService->addToCart($userId, $skuId, $quantity);

        return $this->success($cart->toArray());
    }

    #[OA\Put(path: '/api/portal/cart/{id}', summary: '更新购物车条目数量', security: [['bearerAuth' => []]], tags: ['前台-购物车接口'])]
    #[OA\Parameter(name: 'id', description: '购物车条目ID', in: 'path', required: true, schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UpdateCartQuantityRequest::class))]
    #[OA\Response(
        response: 200,
        description: '修改数量成功',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
                new OA\Property(property: 'data', type: 'object'),
            ]
        )
    )]
    public function updateQuantity(UpdateCartQuantityRequest $request, int $id): JsonResponse
    {
        $userId = $this->getUserId($request);
        $quantity = (int) $request->input('quantity');

        $cart = $this->cartService->updateQuantity($userId, $id, $quantity);

        return $this->success($cart->toArray());
    }

    #[OA\Post(path: '/api/portal/cart/select', summary: '批量或全选购物车条目', security: [['bearerAuth' => []]], tags: ['前台-购物车接口'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SelectCartItemsRequest::class))]
    #[OA\Response(
        response: 200,
        description: '状态更新成功',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
            ]
        )
    )]
    public function select(SelectCartItemsRequest $request): JsonResponse
    {
        $userId = $this->getUserId($request);
        $cartIds = $request->input('cart_ids');
        $isSelected = (bool) $request->input('is_selected');

        if (! empty($cartIds) && is_array($cartIds)) {
            $this->cartService->selectItems($userId, array_map('intval', $cartIds), $isSelected);
        } else {
            $this->cartService->selectAll($userId, $isSelected);
        }

        return $this->success();
    }

    #[OA\Post(path: '/api/portal/cart/remove', summary: '批量删除购物车商品', security: [['bearerAuth' => []]], tags: ['前台-购物车接口'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RemoveCartItemsRequest::class))]
    #[OA\Response(
        response: 200,
        description: '删除成功',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'code', type: 'integer', example: 0),
                new OA\Property(property: 'message', type: 'string', example: 'ok'),
            ]
        )
    )]
    public function destroy(RemoveCartItemsRequest $request): JsonResponse
    {
        $userId = $this->getUserId($request);
        $cartIds = array_map('intval', (array) $request->input('cart_ids'));

        $this->cartService->removeItems($userId, $cartIds);

        return $this->success();
    }
}
