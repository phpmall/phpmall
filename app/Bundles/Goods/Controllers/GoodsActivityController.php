<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsActivityEntity;
use App\Bundles\Goods\Requests\GoodsActivity\GoodsActivityCreateRequest;
use App\Bundles\Goods\Requests\GoodsActivity\GoodsActivityDestroyRequest;
use App\Bundles\Goods\Requests\GoodsActivity\GoodsActivityQueryRequest;
use App\Bundles\Goods\Requests\GoodsActivity\GoodsActivityUpdateRequest;
use App\Bundles\Goods\Responses\GoodsActivity\GoodsActivityDestroyResponse;
use App\Bundles\Goods\Responses\GoodsActivity\GoodsActivityQueryResponse;
use App\Bundles\Goods\Responses\GoodsActivity\GoodsActivityResponse;
use App\Bundles\Goods\Services\GoodsActivityBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsActivityController extends BaseController
{
    #[OA\Post(path: '/goodsActivity/query', summary: '查询商品活动关联列表接口', security: [['bearerAuth' => []]], tags: ['商品活动关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsActivityQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsActivityQueryResponse::class))]
    public function query(GoodsActivityQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsActivityQueryRequest::getGoodsId])) {
                $condition[] = [GoodsActivityEntity::getGoodsId, '=', $requestData[GoodsActivityQueryRequest::getGoodsId]];
            }
            if (isset($requestData[GoodsActivityQueryRequest::getActId])) {
                $condition[] = [GoodsActivityEntity::getActId, '=', $requestData[GoodsActivityQueryRequest::getActId]];
            }

            $goodsActivityBundleService = new GoodsActivityBundleService;
            $result = $goodsActivityBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsActivityResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsActivityQueryResponse($result);
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

    #[OA\Post(path: '/goodsActivity/store', summary: '新增商品活动关联接口', security: [['bearerAuth' => []]], tags: ['商品活动关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsActivityCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsActivityResponse::class))]
    public function store(GoodsActivityCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsActivityEntity($requestData);

            $goodsActivityBundleService = new GoodsActivityBundleService;
            if ($goodsActivityBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsActivity/show', summary: '获取商品活动关联详情接口', security: [['bearerAuth' => []]], tags: ['商品活动关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsActivityResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsActivityBundleService = new GoodsActivityBundleService;
            $goodsActivity = $goodsActivityBundleService->getOneById($id);
            if (empty($goodsActivity)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsActivityResponse($goodsActivity);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsActivity/update', summary: '更新商品活动关联接口', security: [['bearerAuth' => []]], tags: ['商品活动关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsActivityUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsActivityResponse::class))]
    public function update(GoodsActivityUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsActivityBundleService = new GoodsActivityBundleService;
            $goodsActivity = $goodsActivityBundleService->getOneById($id);
            if (empty($goodsActivity)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsActivityEntity($requestData);

            $goodsActivityBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsActivity/destroy', summary: '删除商品活动关联接口', security: [['bearerAuth' => []]], tags: ['商品活动关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsActivityDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsActivityDestroyResponse::class))]
    public function destroy(GoodsActivityDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsActivityBundleService = new GoodsActivityBundleService;
            if ($goodsActivityBundleService->removeByIds($requestData['ids'])) {
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
