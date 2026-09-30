<?php

declare(strict_types=1);

namespace App\Domains\Cart\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Domains\Cart\Entities\CartEntity;
use App\Domains\Cart\Requests\Cart\CartCreateRequest;
use App\Domains\Cart\Requests\Cart\CartDestroyRequest;
use App\Domains\Cart\Requests\Cart\CartQueryRequest;
use App\Domains\Cart\Requests\Cart\CartUpdateRequest;
use App\Domains\Cart\Responses\Cart\CartDestroyResponse;
use App\Domains\Cart\Responses\Cart\CartQueryResponse;
use App\Domains\Cart\Responses\Cart\CartResponse;
use App\Domains\Cart\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class CartController extends BaseController
{
    public function __construct(
        private readonly CartService $cartService,
    ) {}

    #[OA\Post(path: '/cart/search', summary: '查询购物车列表接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CartQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: CartQueryResponse::class),
        ],
    ))]
    public function search(CartQueryRequest $queryRequest): JsonResponse
    {
        $page = \intval($queryRequest->query('page', '1'));
        $pageSize = \intval($queryRequest->query('pageSize', '10'));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[CartQueryRequest::getUserId])) {
                $condition[] = [CartEntity::getUserId, '=', $requestData[CartQueryRequest::getUserId]];
            }
            if (isset($requestData[CartQueryRequest::getMerchantId])) {
                $condition[] = [CartEntity::getMerchantId, '=', $requestData[CartQueryRequest::getMerchantId]];
            }
            if (isset($requestData[CartQueryRequest::getId])) {
                $condition[] = [CartEntity::getId, '=', $requestData[CartQueryRequest::getId]];
            }
            if (isset($requestData[CartQueryRequest::getSkuId])) {
                $condition[] = [CartEntity::getSkuId, '=', $requestData[CartQueryRequest::getSkuId]];
            }

            $result = $this->cartService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = CartResponse::from($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = CartQueryResponse::from($result);
            $response->setFirstPageUrl('');
            $response->setLastPageUrl('');
            $response->setLinks([]);
            $response->setPath('');

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/cart/store', summary: '新增购物车接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CartCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: CartResponse::class),
        ],
    ))]
    public function store(CartCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = CartEntity::from($requestData);

            if ($this->cartService->save($input->toEntity())) {
                DB::commit();

                return $this->success();
            }

            throw new BusinessException(BusinessEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/cart/show', summary: '获取购物车详情接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: CartResponse::class),
        ],
    ))]
    public function show(Request $request): JsonResponse
    {
        $id = \intval($request->query('id', '0'));

        try {
            $cart = $this->cartService->getOneById($id);
            if (empty($cart)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = CartResponse::from($cart);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/cart/update', summary: '更新购物车接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CartUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: CartResponse::class),
        ],
    ))]
    public function update(CartUpdateRequest $updateRequest): JsonResponse
    {
        $id = \intval($updateRequest->query('id', '0'));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $cart = $this->cartService->getOneById($id);
            if (empty($cart)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = CartEntity::from($requestData);

            $this->cartService->updateById($input->toEntity(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::UPDATE_ERROR);
        }
    }

    #[OA\Post(path: '/cart/destroy', summary: '删除购物车接口', security: [['bearerAuth' => []]], tags: ['购物车模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CartDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(
        properties: [
            new OA\Property(property: 'code', type: 'integer', description: '状态码', example: 0),
            new OA\Property(property: 'message', type: 'string', description: '消息', example: 'ok'),
            new OA\Property(property: 'data', ref: CartDestroyResponse::class),
        ],
    ))]
    public function destroy(CartDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            if ($this->cartService->removeByIds($requestData['ids'])) {
                DB::commit();

                return $this->success();
            }

            throw new BusinessException(BusinessEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::DESTROY_ERROR);
        }
    }
}
