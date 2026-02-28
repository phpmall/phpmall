<?php

declare(strict_types=1);

namespace App\Bundles\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Order\Entities\OrderDeliveryGoodsEntity;
use App\Bundles\Order\Requests\OrderDeliveryGoods\OrderDeliveryGoodsCreateRequest;
use App\Bundles\Order\Requests\OrderDeliveryGoods\OrderDeliveryGoodsDestroyRequest;
use App\Bundles\Order\Requests\OrderDeliveryGoods\OrderDeliveryGoodsQueryRequest;
use App\Bundles\Order\Requests\OrderDeliveryGoods\OrderDeliveryGoodsUpdateRequest;
use App\Bundles\Order\Responses\OrderDeliveryGoods\OrderDeliveryGoodsDestroyResponse;
use App\Bundles\Order\Responses\OrderDeliveryGoods\OrderDeliveryGoodsQueryResponse;
use App\Bundles\Order\Responses\OrderDeliveryGoods\OrderDeliveryGoodsResponse;
use App\Bundles\Order\Services\OrderDeliveryGoodsBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderDeliveryGoodsController extends BaseController
{
    #[OA\Post(path: '/orderDeliveryGoods/query', summary: '查询发货商品列表接口', security: [['bearerAuth' => []]], tags: ['发货商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderDeliveryGoodsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderDeliveryGoodsQueryResponse::class))]
    public function query(OrderDeliveryGoodsQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderDeliveryGoodsQueryRequest::getGoodsId])) {
                $condition[] = [OrderDeliveryGoodsEntity::getGoodsId, '=', $requestData[OrderDeliveryGoodsQueryRequest::getGoodsId]];
            }
            if (isset($requestData[OrderDeliveryGoodsQueryRequest::getGoodsId])) {
                $condition[] = [OrderDeliveryGoodsEntity::getGoodsId, '=', $requestData[OrderDeliveryGoodsQueryRequest::getGoodsId]];
            }
            if (isset($requestData[OrderDeliveryGoodsQueryRequest::getRecId])) {
                $condition[] = [OrderDeliveryGoodsEntity::getRecId, '=', $requestData[OrderDeliveryGoodsQueryRequest::getRecId]];
            }

            $orderDeliveryGoodsBundleService = new OrderDeliveryGoodsBundleService;
            $result = $orderDeliveryGoodsBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderDeliveryGoodsResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new OrderDeliveryGoodsQueryResponse($result);
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

    #[OA\Post(path: '/orderDeliveryGoods/store', summary: '新增发货商品接口', security: [['bearerAuth' => []]], tags: ['发货商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderDeliveryGoodsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderDeliveryGoodsResponse::class))]
    public function store(OrderDeliveryGoodsCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new OrderDeliveryGoodsEntity($requestData);

            $orderDeliveryGoodsBundleService = new OrderDeliveryGoodsBundleService;
            if ($orderDeliveryGoodsBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderDeliveryGoods/show', summary: '获取发货商品详情接口', security: [['bearerAuth' => []]], tags: ['发货商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderDeliveryGoodsResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $orderDeliveryGoodsBundleService = new OrderDeliveryGoodsBundleService;
            $orderDeliveryGoods = $orderDeliveryGoodsBundleService->getOneById($id);
            if (empty($orderDeliveryGoods)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new OrderDeliveryGoodsResponse($orderDeliveryGoods);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderDeliveryGoods/update', summary: '更新发货商品接口', security: [['bearerAuth' => []]], tags: ['发货商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderDeliveryGoodsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderDeliveryGoodsResponse::class))]
    public function update(OrderDeliveryGoodsUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderDeliveryGoodsBundleService = new OrderDeliveryGoodsBundleService;
            $orderDeliveryGoods = $orderDeliveryGoodsBundleService->getOneById($id);
            if (empty($orderDeliveryGoods)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new OrderDeliveryGoodsEntity($requestData);

            $orderDeliveryGoodsBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/orderDeliveryGoods/destroy', summary: '删除发货商品接口', security: [['bearerAuth' => []]], tags: ['发货商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderDeliveryGoodsDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderDeliveryGoodsDestroyResponse::class))]
    public function destroy(OrderDeliveryGoodsDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $orderDeliveryGoodsBundleService = new OrderDeliveryGoodsBundleService;
            if ($orderDeliveryGoodsBundleService->removeByIds($requestData['ids'])) {
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
