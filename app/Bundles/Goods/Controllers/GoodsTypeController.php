<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsTypeEntity;
use App\Bundles\Goods\Requests\GoodsType\GoodsTypeCreateRequest;
use App\Bundles\Goods\Requests\GoodsType\GoodsTypeDestroyRequest;
use App\Bundles\Goods\Requests\GoodsType\GoodsTypeQueryRequest;
use App\Bundles\Goods\Requests\GoodsType\GoodsTypeUpdateRequest;
use App\Bundles\Goods\Responses\GoodsType\GoodsTypeDestroyResponse;
use App\Bundles\Goods\Responses\GoodsType\GoodsTypeQueryResponse;
use App\Bundles\Goods\Responses\GoodsType\GoodsTypeResponse;
use App\Bundles\Goods\Services\GoodsTypeBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsTypeController extends BaseController
{
    #[OA\Post(path: '/goodsType/query', summary: '查询商品类型列表接口', security: [['bearerAuth' => []]], tags: ['商品类型模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsTypeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsTypeQueryResponse::class))]
    public function query(GoodsTypeQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsTypeQueryRequest::getCatId])) {
                $condition[] = [GoodsTypeEntity::getCatId, '=', $requestData[GoodsTypeQueryRequest::getCatId]];
            }

            $goodsTypeBundleService = new GoodsTypeBundleService;
            $result = $goodsTypeBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsTypeResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsTypeQueryResponse($result);
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

    #[OA\Post(path: '/goodsType/store', summary: '新增商品类型接口', security: [['bearerAuth' => []]], tags: ['商品类型模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsTypeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsTypeResponse::class))]
    public function store(GoodsTypeCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsTypeEntity($requestData);

            $goodsTypeBundleService = new GoodsTypeBundleService;
            if ($goodsTypeBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsType/show', summary: '获取商品类型详情接口', security: [['bearerAuth' => []]], tags: ['商品类型模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsTypeResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsTypeBundleService = new GoodsTypeBundleService;
            $goodsType = $goodsTypeBundleService->getOneById($id);
            if (empty($goodsType)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsTypeResponse($goodsType);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsType/update', summary: '更新商品类型接口', security: [['bearerAuth' => []]], tags: ['商品类型模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsTypeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsTypeResponse::class))]
    public function update(GoodsTypeUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsTypeBundleService = new GoodsTypeBundleService;
            $goodsType = $goodsTypeBundleService->getOneById($id);
            if (empty($goodsType)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsTypeEntity($requestData);

            $goodsTypeBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsType/destroy', summary: '删除商品类型接口', security: [['bearerAuth' => []]], tags: ['商品类型模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsTypeDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsTypeDestroyResponse::class))]
    public function destroy(GoodsTypeDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsTypeBundleService = new GoodsTypeBundleService;
            if ($goodsTypeBundleService->removeByIds($requestData['ids'])) {
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
