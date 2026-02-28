<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsLinkGoodsEntity;
use App\Bundles\Goods\Requests\GoodsLinkGoods\GoodsLinkGoodsCreateRequest;
use App\Bundles\Goods\Requests\GoodsLinkGoods\GoodsLinkGoodsDestroyRequest;
use App\Bundles\Goods\Requests\GoodsLinkGoods\GoodsLinkGoodsQueryRequest;
use App\Bundles\Goods\Requests\GoodsLinkGoods\GoodsLinkGoodsUpdateRequest;
use App\Bundles\Goods\Responses\GoodsLinkGoods\GoodsLinkGoodsDestroyResponse;
use App\Bundles\Goods\Responses\GoodsLinkGoods\GoodsLinkGoodsQueryResponse;
use App\Bundles\Goods\Responses\GoodsLinkGoods\GoodsLinkGoodsResponse;
use App\Bundles\Goods\Services\GoodsLinkGoodsBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsLinkGoodsController extends BaseController
{
    #[OA\Post(path: '/goodsLinkGoods/query', summary: '查询关联商品列表接口', security: [['bearerAuth' => []]], tags: ['关联商品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsLinkGoodsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsLinkGoodsQueryResponse::class))]
    public function query(GoodsLinkGoodsQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsLinkGoodsQueryRequest::getLinkGoodsId])) {
                $condition[] = [GoodsLinkGoodsEntity::getLinkGoodsId, '=', $requestData[GoodsLinkGoodsQueryRequest::getLinkGoodsId]];
            }
            if (isset($requestData[GoodsLinkGoodsQueryRequest::getId])) {
                $condition[] = [GoodsLinkGoodsEntity::getId, '=', $requestData[GoodsLinkGoodsQueryRequest::getId]];
            }

            $goodsLinkGoodsBundleService = new GoodsLinkGoodsBundleService;
            $result = $goodsLinkGoodsBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsLinkGoodsResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsLinkGoodsQueryResponse($result);
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

    #[OA\Post(path: '/goodsLinkGoods/store', summary: '新增关联商品接口', security: [['bearerAuth' => []]], tags: ['关联商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsLinkGoodsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsLinkGoodsResponse::class))]
    public function store(GoodsLinkGoodsCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsLinkGoodsEntity($requestData);

            $goodsLinkGoodsBundleService = new GoodsLinkGoodsBundleService;
            if ($goodsLinkGoodsBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsLinkGoods/show', summary: '获取关联商品详情接口', security: [['bearerAuth' => []]], tags: ['关联商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsLinkGoodsResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsLinkGoodsBundleService = new GoodsLinkGoodsBundleService;
            $goodsLinkGoods = $goodsLinkGoodsBundleService->getOneById($id);
            if (empty($goodsLinkGoods)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsLinkGoodsResponse($goodsLinkGoods);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsLinkGoods/update', summary: '更新关联商品接口', security: [['bearerAuth' => []]], tags: ['关联商品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsLinkGoodsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsLinkGoodsResponse::class))]
    public function update(GoodsLinkGoodsUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsLinkGoodsBundleService = new GoodsLinkGoodsBundleService;
            $goodsLinkGoods = $goodsLinkGoodsBundleService->getOneById($id);
            if (empty($goodsLinkGoods)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsLinkGoodsEntity($requestData);

            $goodsLinkGoodsBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsLinkGoods/destroy', summary: '删除关联商品接口', security: [['bearerAuth' => []]], tags: ['关联商品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsLinkGoodsDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsLinkGoodsDestroyResponse::class))]
    public function destroy(GoodsLinkGoodsDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsLinkGoodsBundleService = new GoodsLinkGoodsBundleService;
            if ($goodsLinkGoodsBundleService->removeByIds($requestData['ids'])) {
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
