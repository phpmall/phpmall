<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopStatsEntity;
use App\Bundles\Shop\Requests\ShopStats\ShopStatsCreateRequest;
use App\Bundles\Shop\Requests\ShopStats\ShopStatsDestroyRequest;
use App\Bundles\Shop\Requests\ShopStats\ShopStatsQueryRequest;
use App\Bundles\Shop\Requests\ShopStats\ShopStatsUpdateRequest;
use App\Bundles\Shop\Responses\ShopStats\ShopStatsDestroyResponse;
use App\Bundles\Shop\Responses\ShopStats\ShopStatsQueryResponse;
use App\Bundles\Shop\Responses\ShopStats\ShopStatsResponse;
use App\Bundles\Shop\Services\ShopStatsBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopStatsController extends BaseController
{
    #[OA\Post(path: '/shopStats/query', summary: '查询统计列表接口', security: [['bearerAuth' => []]], tags: ['统计模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopStatsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopStatsQueryResponse::class))]
    public function query(ShopStatsQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopStatsQueryRequest::getId])) {
                $condition[] = [ShopStatsEntity::getId, '=', $requestData[ShopStatsQueryRequest::getId]];
            }
            if (isset($requestData[ShopStatsQueryRequest::getAccessTime])) {
                $condition[] = [ShopStatsEntity::getAccessTime, '=', $requestData[ShopStatsQueryRequest::getAccessTime]];
            }

            $shopStatsBundleService = new ShopStatsBundleService;
            $result = $shopStatsBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopStatsResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopStatsQueryResponse($result);
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

    #[OA\Post(path: '/shopStats/store', summary: '新增统计接口', security: [['bearerAuth' => []]], tags: ['统计模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopStatsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopStatsResponse::class))]
    public function store(ShopStatsCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopStatsEntity($requestData);

            $shopStatsBundleService = new ShopStatsBundleService;
            if ($shopStatsBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopStats/show', summary: '获取统计详情接口', security: [['bearerAuth' => []]], tags: ['统计模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopStatsResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopStatsBundleService = new ShopStatsBundleService;
            $shopStats = $shopStatsBundleService->getOneById($id);
            if (empty($shopStats)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopStatsResponse($shopStats);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopStats/update', summary: '更新统计接口', security: [['bearerAuth' => []]], tags: ['统计模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopStatsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopStatsResponse::class))]
    public function update(ShopStatsUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopStatsBundleService = new ShopStatsBundleService;
            $shopStats = $shopStatsBundleService->getOneById($id);
            if (empty($shopStats)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopStatsEntity($requestData);

            $shopStatsBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopStats/destroy', summary: '删除统计接口', security: [['bearerAuth' => []]], tags: ['统计模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopStatsDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopStatsDestroyResponse::class))]
    public function destroy(ShopStatsDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopStatsBundleService = new ShopStatsBundleService;
            if ($shopStatsBundleService->removeByIds($requestData['ids'])) {
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
