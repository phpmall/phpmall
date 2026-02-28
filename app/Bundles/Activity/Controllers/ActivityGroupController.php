<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Activity\Entities\ActivityGroupEntity;
use App\Bundles\Activity\Requests\ActivityGroup\ActivityGroupCreateRequest;
use App\Bundles\Activity\Requests\ActivityGroup\ActivityGroupDestroyRequest;
use App\Bundles\Activity\Requests\ActivityGroup\ActivityGroupQueryRequest;
use App\Bundles\Activity\Requests\ActivityGroup\ActivityGroupUpdateRequest;
use App\Bundles\Activity\Responses\ActivityGroup\ActivityGroupDestroyResponse;
use App\Bundles\Activity\Responses\ActivityGroup\ActivityGroupQueryResponse;
use App\Bundles\Activity\Responses\ActivityGroup\ActivityGroupResponse;
use App\Bundles\Activity\Services\ActivityGroupBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ActivityGroupController extends BaseController
{
    #[OA\Post(path: '/activityGroup/query', summary: '查询团购活动列表接口', security: [['bearerAuth' => []]], tags: ['团购活动模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityGroupQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityGroupQueryResponse::class))]
    public function query(ActivityGroupQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ActivityGroupQueryRequest::getGoodsId])) {
                $condition[] = [ActivityGroupEntity::getGoodsId, '=', $requestData[ActivityGroupQueryRequest::getGoodsId]];
            }
            if (isset($requestData[ActivityGroupQueryRequest::getId])) {
                $condition[] = [ActivityGroupEntity::getId, '=', $requestData[ActivityGroupQueryRequest::getId]];
            }

            $activityGroupBundleService = new ActivityGroupBundleService;
            $result = $activityGroupBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ActivityGroupResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ActivityGroupQueryResponse($result);
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

    #[OA\Post(path: '/activityGroup/store', summary: '新增团购活动接口', security: [['bearerAuth' => []]], tags: ['团购活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityGroupCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityGroupResponse::class))]
    public function store(ActivityGroupCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ActivityGroupEntity($requestData);

            $activityGroupBundleService = new ActivityGroupBundleService;
            if ($activityGroupBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/activityGroup/show', summary: '获取团购活动详情接口', security: [['bearerAuth' => []]], tags: ['团购活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityGroupResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $activityGroupBundleService = new ActivityGroupBundleService;
            $activityGroup = $activityGroupBundleService->getOneById($id);
            if (empty($activityGroup)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ActivityGroupResponse($activityGroup);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/activityGroup/update', summary: '更新团购活动接口', security: [['bearerAuth' => []]], tags: ['团购活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityGroupUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityGroupResponse::class))]
    public function update(ActivityGroupUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $activityGroupBundleService = new ActivityGroupBundleService;
            $activityGroup = $activityGroupBundleService->getOneById($id);
            if (empty($activityGroup)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ActivityGroupEntity($requestData);

            $activityGroupBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/activityGroup/destroy', summary: '删除团购活动接口', security: [['bearerAuth' => []]], tags: ['团购活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityGroupDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityGroupDestroyResponse::class))]
    public function destroy(ActivityGroupDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $activityGroupBundleService = new ActivityGroupBundleService;
            if ($activityGroupBundleService->removeByIds($requestData['ids'])) {
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
