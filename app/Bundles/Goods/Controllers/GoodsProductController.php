<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsProductEntity;
use App\Bundles\Goods\Requests\GoodsProduct\GoodsProductCreateRequest;
use App\Bundles\Goods\Requests\GoodsProduct\GoodsProductDestroyRequest;
use App\Bundles\Goods\Requests\GoodsProduct\GoodsProductQueryRequest;
use App\Bundles\Goods\Requests\GoodsProduct\GoodsProductUpdateRequest;
use App\Bundles\Goods\Responses\GoodsProduct\GoodsProductDestroyResponse;
use App\Bundles\Goods\Responses\GoodsProduct\GoodsProductQueryResponse;
use App\Bundles\Goods\Responses\GoodsProduct\GoodsProductResponse;
use App\Bundles\Goods\Services\GoodsProductBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsProductController extends BaseController
{
    #[OA\Post(path: '/goodsProduct/query', summary: '查询商品货品列表接口', security: [['bearerAuth' => []]], tags: ['商品货品模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsProductQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsProductQueryResponse::class))]
    public function query(GoodsProductQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsProductQueryRequest::getProductId])) {
                $condition[] = [GoodsProductEntity::getProductId, '=', $requestData[GoodsProductQueryRequest::getProductId]];
            }

            $goodsProductBundleService = new GoodsProductBundleService;
            $result = $goodsProductBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsProductResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsProductQueryResponse($result);
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

    #[OA\Post(path: '/goodsProduct/store', summary: '新增商品货品接口', security: [['bearerAuth' => []]], tags: ['商品货品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsProductCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsProductResponse::class))]
    public function store(GoodsProductCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsProductEntity($requestData);

            $goodsProductBundleService = new GoodsProductBundleService;
            if ($goodsProductBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsProduct/show', summary: '获取商品货品详情接口', security: [['bearerAuth' => []]], tags: ['商品货品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsProductResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsProductBundleService = new GoodsProductBundleService;
            $goodsProduct = $goodsProductBundleService->getOneById($id);
            if (empty($goodsProduct)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsProductResponse($goodsProduct);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsProduct/update', summary: '更新商品货品接口', security: [['bearerAuth' => []]], tags: ['商品货品模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsProductUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsProductResponse::class))]
    public function update(GoodsProductUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsProductBundleService = new GoodsProductBundleService;
            $goodsProduct = $goodsProductBundleService->getOneById($id);
            if (empty($goodsProduct)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsProductEntity($requestData);

            $goodsProductBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsProduct/destroy', summary: '删除商品货品接口', security: [['bearerAuth' => []]], tags: ['商品货品模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsProductDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsProductDestroyResponse::class))]
    public function destroy(GoodsProductDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsProductBundleService = new GoodsProductBundleService;
            if ($goodsProductBundleService->removeByIds($requestData['ids'])) {
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
