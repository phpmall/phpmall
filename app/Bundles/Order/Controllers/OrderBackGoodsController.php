<?php

declare(strict_types=1);

namespace App\Bundles\Order\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Order\Entities\OrderBackGoodsEntity;
use App\Bundles\Order\Requests\OrderBackGoods\OrderBackGoodsCreateRequest;
use App\Bundles\Order\Requests\OrderBackGoods\OrderBackGoodsDestroyRequest;
use App\Bundles\Order\Requests\OrderBackGoods\OrderBackGoodsQueryRequest;
use App\Bundles\Order\Requests\OrderBackGoods\OrderBackGoodsUpdateRequest;
use App\Bundles\Order\Responses\OrderBackGoods\OrderBackGoodsDestroyResponse;
use App\Bundles\Order\Responses\OrderBackGoods\OrderBackGoodsQueryResponse;
use App\Bundles\Order\Responses\OrderBackGoods\OrderBackGoodsResponse;
use App\Bundles\Order\Services\OrderBackGoodsBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class OrderBackGoodsController extends BaseController
{
    #[OA\Post(path: '/orderBackGoods/query', summary: '查询退货商品列表接口', security: [['bearerAuth' => []]], tags: ['退货商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderBackGoodsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderBackGoodsQueryResponse::class))]
    public function query(OrderBackGoodsQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[OrderBackGoodsQueryRequest::getBackId])) {
                $condition[] = [OrderBackGoodsEntity::getBackId, '=', $requestData[OrderBackGoodsQueryRequest::getBackId]];
            }
            if (isset($requestData[OrderBackGoodsQueryRequest::getGoodsId])) {
                $condition[] = [OrderBackGoodsEntity::getGoodsId, '=', $requestData[OrderBackGoodsQueryRequest::getGoodsId]];
            }
            if (isset($requestData[OrderBackGoodsQueryRequest::getRecId])) {
                $condition[] = [OrderBackGoodsEntity::getRecId, '=', $requestData[OrderBackGoodsQueryRequest::getRecId]];
            }

            $orderBackGoodsBundleService = new OrderBackGoodsBundleService;
            $result = $orderBackGoodsBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new OrderBackGoodsResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new OrderBackGoodsQueryResponse($result);
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

    #[OA\Post(path: '/orderBackGoods/store', summary: '新增退货商品接口', security: [['bearerAuth' => []]], tags: ['退货商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderBackGoodsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderBackGoodsResponse::class))]
    public function store(OrderBackGoodsCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new OrderBackGoodsEntity($requestData);

            $orderBackGoodsBundleService = new OrderBackGoodsBundleService;
            if ($orderBackGoodsBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/orderBackGoods/show', summary: '获取退货商品详情接口', security: [['bearerAuth' => []]], tags: ['退货商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderBackGoodsResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $orderBackGoodsBundleService = new OrderBackGoodsBundleService;
            $orderBackGoods = $orderBackGoodsBundleService->getOneById($id);
            if (empty($orderBackGoods)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new OrderBackGoodsResponse($orderBackGoods);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/orderBackGoods/update', summary: '更新退货商品接口', security: [['bearerAuth' => []]], tags: ['退货商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderBackGoodsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderBackGoodsResponse::class))]
    public function update(OrderBackGoodsUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $orderBackGoodsBundleService = new OrderBackGoodsBundleService;
            $orderBackGoods = $orderBackGoodsBundleService->getOneById($id);
            if (empty($orderBackGoods)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new OrderBackGoodsEntity($requestData);

            $orderBackGoodsBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/orderBackGoods/destroy', summary: '删除退货商品接口', security: [['bearerAuth' => []]], tags: ['退货商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: OrderBackGoodsDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: OrderBackGoodsDestroyResponse::class))]
    public function destroy(OrderBackGoodsDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $orderBackGoodsBundleService = new OrderBackGoodsBundleService;
            if ($orderBackGoodsBundleService->removeByIds($requestData['ids'])) {
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
