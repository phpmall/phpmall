<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsVolumePriceEntity;
use App\Bundles\Goods\Requests\GoodsVolumePrice\GoodsVolumePriceCreateRequest;
use App\Bundles\Goods\Requests\GoodsVolumePrice\GoodsVolumePriceDestroyRequest;
use App\Bundles\Goods\Requests\GoodsVolumePrice\GoodsVolumePriceQueryRequest;
use App\Bundles\Goods\Requests\GoodsVolumePrice\GoodsVolumePriceUpdateRequest;
use App\Bundles\Goods\Responses\GoodsVolumePrice\GoodsVolumePriceDestroyResponse;
use App\Bundles\Goods\Responses\GoodsVolumePrice\GoodsVolumePriceQueryResponse;
use App\Bundles\Goods\Responses\GoodsVolumePrice\GoodsVolumePriceResponse;
use App\Bundles\Goods\Services\GoodsVolumePriceBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsVolumePriceController extends BaseController
{
    #[OA\Post(path: '/goodsVolumePrice/query', summary: '查询商品批量价格列表接口', security: [['bearerAuth' => []]], tags: ['商品批量价格模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsVolumePriceQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsVolumePriceQueryResponse::class))]
    public function query(GoodsVolumePriceQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsVolumePriceQueryRequest::getVolumeNumber])) {
                $condition[] = [GoodsVolumePriceEntity::getVolumeNumber, '=', $requestData[GoodsVolumePriceQueryRequest::getVolumeNumber]];
            }
            if (isset($requestData[GoodsVolumePriceQueryRequest::getId])) {
                $condition[] = [GoodsVolumePriceEntity::getId, '=', $requestData[GoodsVolumePriceQueryRequest::getId]];
            }

            $goodsVolumePriceBundleService = new GoodsVolumePriceBundleService;
            $result = $goodsVolumePriceBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsVolumePriceResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsVolumePriceQueryResponse($result);
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

    #[OA\Post(path: '/goodsVolumePrice/store', summary: '新增商品批量价格接口', security: [['bearerAuth' => []]], tags: ['商品批量价格模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsVolumePriceCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsVolumePriceResponse::class))]
    public function store(GoodsVolumePriceCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsVolumePriceEntity($requestData);

            $goodsVolumePriceBundleService = new GoodsVolumePriceBundleService;
            if ($goodsVolumePriceBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsVolumePrice/show', summary: '获取商品批量价格详情接口', security: [['bearerAuth' => []]], tags: ['商品批量价格模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsVolumePriceResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsVolumePriceBundleService = new GoodsVolumePriceBundleService;
            $goodsVolumePrice = $goodsVolumePriceBundleService->getOneById($id);
            if (empty($goodsVolumePrice)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsVolumePriceResponse($goodsVolumePrice);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsVolumePrice/update', summary: '更新商品批量价格接口', security: [['bearerAuth' => []]], tags: ['商品批量价格模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsVolumePriceUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsVolumePriceResponse::class))]
    public function update(GoodsVolumePriceUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsVolumePriceBundleService = new GoodsVolumePriceBundleService;
            $goodsVolumePrice = $goodsVolumePriceBundleService->getOneById($id);
            if (empty($goodsVolumePrice)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsVolumePriceEntity($requestData);

            $goodsVolumePriceBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsVolumePrice/destroy', summary: '删除商品批量价格接口', security: [['bearerAuth' => []]], tags: ['商品批量价格模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsVolumePriceDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsVolumePriceDestroyResponse::class))]
    public function destroy(GoodsVolumePriceDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsVolumePriceBundleService = new GoodsVolumePriceBundleService;
            if ($goodsVolumePriceBundleService->removeByIds($requestData['ids'])) {
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
