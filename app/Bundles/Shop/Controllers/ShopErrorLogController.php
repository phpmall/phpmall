<?php

declare(strict_types=1);

namespace App\Bundles\Shop\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Shop\Entities\ShopErrorLogEntity;
use App\Bundles\Shop\Requests\ShopErrorLog\ShopErrorLogCreateRequest;
use App\Bundles\Shop\Requests\ShopErrorLog\ShopErrorLogDestroyRequest;
use App\Bundles\Shop\Requests\ShopErrorLog\ShopErrorLogQueryRequest;
use App\Bundles\Shop\Requests\ShopErrorLog\ShopErrorLogUpdateRequest;
use App\Bundles\Shop\Responses\ShopErrorLog\ShopErrorLogDestroyResponse;
use App\Bundles\Shop\Responses\ShopErrorLog\ShopErrorLogQueryResponse;
use App\Bundles\Shop\Responses\ShopErrorLog\ShopErrorLogResponse;
use App\Bundles\Shop\Services\ShopErrorLogBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ShopErrorLogController extends BaseController
{
    #[OA\Post(path: '/shopErrorLog/query', summary: '查询错误日志列表接口', security: [['bearerAuth' => []]], tags: ['错误日志模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopErrorLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopErrorLogQueryResponse::class))]
    public function query(ShopErrorLogQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ShopErrorLogQueryRequest::getId])) {
                $condition[] = [ShopErrorLogEntity::getId, '=', $requestData[ShopErrorLogQueryRequest::getId]];
            }

            $shopErrorLogBundleService = new ShopErrorLogBundleService;
            $result = $shopErrorLogBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ShopErrorLogResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ShopErrorLogQueryResponse($result);
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

    #[OA\Post(path: '/shopErrorLog/store', summary: '新增错误日志接口', security: [['bearerAuth' => []]], tags: ['错误日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopErrorLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopErrorLogResponse::class))]
    public function store(ShopErrorLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ShopErrorLogEntity($requestData);

            $shopErrorLogBundleService = new ShopErrorLogBundleService;
            if ($shopErrorLogBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/shopErrorLog/show', summary: '获取错误日志详情接口', security: [['bearerAuth' => []]], tags: ['错误日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopErrorLogResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $shopErrorLogBundleService = new ShopErrorLogBundleService;
            $shopErrorLog = $shopErrorLogBundleService->getOneById($id);
            if (empty($shopErrorLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ShopErrorLogResponse($shopErrorLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/shopErrorLog/update', summary: '更新错误日志接口', security: [['bearerAuth' => []]], tags: ['错误日志模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopErrorLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopErrorLogResponse::class))]
    public function update(ShopErrorLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $shopErrorLogBundleService = new ShopErrorLogBundleService;
            $shopErrorLog = $shopErrorLogBundleService->getOneById($id);
            if (empty($shopErrorLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ShopErrorLogEntity($requestData);

            $shopErrorLogBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/shopErrorLog/destroy', summary: '删除错误日志接口', security: [['bearerAuth' => []]], tags: ['错误日志模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ShopErrorLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ShopErrorLogDestroyResponse::class))]
    public function destroy(ShopErrorLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $shopErrorLogBundleService = new ShopErrorLogBundleService;
            if ($shopErrorLogBundleService->removeByIds($requestData['ids'])) {
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
