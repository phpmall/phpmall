<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsBrandEntity;
use App\Bundles\Goods\Requests\GoodsBrand\GoodsBrandCreateRequest;
use App\Bundles\Goods\Requests\GoodsBrand\GoodsBrandDestroyRequest;
use App\Bundles\Goods\Requests\GoodsBrand\GoodsBrandQueryRequest;
use App\Bundles\Goods\Requests\GoodsBrand\GoodsBrandUpdateRequest;
use App\Bundles\Goods\Responses\GoodsBrand\GoodsBrandDestroyResponse;
use App\Bundles\Goods\Responses\GoodsBrand\GoodsBrandQueryResponse;
use App\Bundles\Goods\Responses\GoodsBrand\GoodsBrandResponse;
use App\Bundles\Goods\Services\GoodsBrandBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsBrandController extends BaseController
{
    #[OA\Post(path: '/goodsBrand/query', summary: '查询商品品牌列表接口', security: [['bearerAuth' => []]], tags: ['商品品牌模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsBrandQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsBrandQueryResponse::class))]
    public function query(GoodsBrandQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsBrandQueryRequest::getIsShow])) {
                $condition[] = [GoodsBrandEntity::getIsShow, '=', $requestData[GoodsBrandQueryRequest::getIsShow]];
            }
            if (isset($requestData[GoodsBrandQueryRequest::getBrandId])) {
                $condition[] = [GoodsBrandEntity::getBrandId, '=', $requestData[GoodsBrandQueryRequest::getBrandId]];
            }

            $goodsBrandBundleService = new GoodsBrandBundleService;
            $result = $goodsBrandBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsBrandResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsBrandQueryResponse($result);
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

    #[OA\Post(path: '/goodsBrand/store', summary: '新增商品品牌接口', security: [['bearerAuth' => []]], tags: ['商品品牌模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsBrandCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsBrandResponse::class))]
    public function store(GoodsBrandCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsBrandEntity($requestData);

            $goodsBrandBundleService = new GoodsBrandBundleService;
            if ($goodsBrandBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsBrand/show', summary: '获取商品品牌详情接口', security: [['bearerAuth' => []]], tags: ['商品品牌模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsBrandResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsBrandBundleService = new GoodsBrandBundleService;
            $goodsBrand = $goodsBrandBundleService->getOneById($id);
            if (empty($goodsBrand)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsBrandResponse($goodsBrand);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsBrand/update', summary: '更新商品品牌接口', security: [['bearerAuth' => []]], tags: ['商品品牌模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsBrandUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsBrandResponse::class))]
    public function update(GoodsBrandUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsBrandBundleService = new GoodsBrandBundleService;
            $goodsBrand = $goodsBrandBundleService->getOneById($id);
            if (empty($goodsBrand)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsBrandEntity($requestData);

            $goodsBrandBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsBrand/destroy', summary: '删除商品品牌接口', security: [['bearerAuth' => []]], tags: ['商品品牌模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsBrandDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsBrandDestroyResponse::class))]
    public function destroy(GoodsBrandDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsBrandBundleService = new GoodsBrandBundleService;
            if ($goodsBrandBundleService->removeByIds($requestData['ids'])) {
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
