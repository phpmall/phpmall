<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Activity\Entities\ActivityPackageEntity;
use App\Bundles\Activity\Requests\ActivityPackage\ActivityPackageCreateRequest;
use App\Bundles\Activity\Requests\ActivityPackage\ActivityPackageDestroyRequest;
use App\Bundles\Activity\Requests\ActivityPackage\ActivityPackageQueryRequest;
use App\Bundles\Activity\Requests\ActivityPackage\ActivityPackageUpdateRequest;
use App\Bundles\Activity\Responses\ActivityPackage\ActivityPackageDestroyResponse;
use App\Bundles\Activity\Responses\ActivityPackage\ActivityPackageQueryResponse;
use App\Bundles\Activity\Responses\ActivityPackage\ActivityPackageResponse;
use App\Bundles\Activity\Services\ActivityPackageBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ActivityPackageController extends BaseController
{
    #[OA\Post(path: '/activityPackage/query', summary: '查询超值礼包活动列表接口', security: [['bearerAuth' => []]], tags: ['超值礼包活动模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityPackageQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityPackageQueryResponse::class))]
    public function query(ActivityPackageQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ActivityPackageQueryRequest::getProductId])) {
                $condition[] = [ActivityPackageEntity::getProductId, '=', $requestData[ActivityPackageQueryRequest::getProductId]];
            }
            if (isset($requestData[ActivityPackageQueryRequest::getId])) {
                $condition[] = [ActivityPackageEntity::getId, '=', $requestData[ActivityPackageQueryRequest::getId]];
            }

            $activityPackageBundleService = new ActivityPackageBundleService;
            $result = $activityPackageBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ActivityPackageResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ActivityPackageQueryResponse($result);
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

    #[OA\Post(path: '/activityPackage/store', summary: '新增超值礼包活动接口', security: [['bearerAuth' => []]], tags: ['超值礼包活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityPackageCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityPackageResponse::class))]
    public function store(ActivityPackageCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ActivityPackageEntity($requestData);

            $activityPackageBundleService = new ActivityPackageBundleService;
            if ($activityPackageBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/activityPackage/show', summary: '获取超值礼包活动详情接口', security: [['bearerAuth' => []]], tags: ['超值礼包活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityPackageResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $activityPackageBundleService = new ActivityPackageBundleService;
            $activityPackage = $activityPackageBundleService->getOneById($id);
            if (empty($activityPackage)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ActivityPackageResponse($activityPackage);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/activityPackage/update', summary: '更新超值礼包活动接口', security: [['bearerAuth' => []]], tags: ['超值礼包活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityPackageUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityPackageResponse::class))]
    public function update(ActivityPackageUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $activityPackageBundleService = new ActivityPackageBundleService;
            $activityPackage = $activityPackageBundleService->getOneById($id);
            if (empty($activityPackage)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ActivityPackageEntity($requestData);

            $activityPackageBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/activityPackage/destroy', summary: '删除超值礼包活动接口', security: [['bearerAuth' => []]], tags: ['超值礼包活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityPackageDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityPackageDestroyResponse::class))]
    public function destroy(ActivityPackageDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $activityPackageBundleService = new ActivityPackageBundleService;
            if ($activityPackageBundleService->removeByIds($requestData['ids'])) {
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
