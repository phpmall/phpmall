<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Activity\Entities\ActivitySnatchEntity;
use App\Bundles\Activity\Requests\ActivitySnatch\ActivitySnatchCreateRequest;
use App\Bundles\Activity\Requests\ActivitySnatch\ActivitySnatchDestroyRequest;
use App\Bundles\Activity\Requests\ActivitySnatch\ActivitySnatchQueryRequest;
use App\Bundles\Activity\Requests\ActivitySnatch\ActivitySnatchUpdateRequest;
use App\Bundles\Activity\Responses\ActivitySnatch\ActivitySnatchDestroyResponse;
use App\Bundles\Activity\Responses\ActivitySnatch\ActivitySnatchQueryResponse;
use App\Bundles\Activity\Responses\ActivitySnatch\ActivitySnatchResponse;
use App\Bundles\Activity\Services\ActivitySnatchBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ActivitySnatchController extends BaseController
{
    #[OA\Post(path: '/activitySnatch/query', summary: '查询夺宝奇兵活动列表接口', security: [['bearerAuth' => []]], tags: ['夺宝奇兵活动模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivitySnatchQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivitySnatchQueryResponse::class))]
    public function query(ActivitySnatchQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ActivitySnatchQueryRequest::getSnatchId])) {
                $condition[] = [ActivitySnatchEntity::getSnatchId, '=', $requestData[ActivitySnatchQueryRequest::getSnatchId]];
            }
            if (isset($requestData[ActivitySnatchQueryRequest::getLogId])) {
                $condition[] = [ActivitySnatchEntity::getLogId, '=', $requestData[ActivitySnatchQueryRequest::getLogId]];
            }

            $activitySnatchBundleService = new ActivitySnatchBundleService;
            $result = $activitySnatchBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ActivitySnatchResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ActivitySnatchQueryResponse($result);
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

    #[OA\Post(path: '/activitySnatch/store', summary: '新增夺宝奇兵活动接口', security: [['bearerAuth' => []]], tags: ['夺宝奇兵活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivitySnatchCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivitySnatchResponse::class))]
    public function store(ActivitySnatchCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ActivitySnatchEntity($requestData);

            $activitySnatchBundleService = new ActivitySnatchBundleService;
            if ($activitySnatchBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/activitySnatch/show', summary: '获取夺宝奇兵活动详情接口', security: [['bearerAuth' => []]], tags: ['夺宝奇兵活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivitySnatchResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $activitySnatchBundleService = new ActivitySnatchBundleService;
            $activitySnatch = $activitySnatchBundleService->getOneById($id);
            if (empty($activitySnatch)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ActivitySnatchResponse($activitySnatch);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/activitySnatch/update', summary: '更新夺宝奇兵活动接口', security: [['bearerAuth' => []]], tags: ['夺宝奇兵活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivitySnatchUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivitySnatchResponse::class))]
    public function update(ActivitySnatchUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $activitySnatchBundleService = new ActivitySnatchBundleService;
            $activitySnatch = $activitySnatchBundleService->getOneById($id);
            if (empty($activitySnatch)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ActivitySnatchEntity($requestData);

            $activitySnatchBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/activitySnatch/destroy', summary: '删除夺宝奇兵活动接口', security: [['bearerAuth' => []]], tags: ['夺宝奇兵活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivitySnatchDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivitySnatchDestroyResponse::class))]
    public function destroy(ActivitySnatchDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $activitySnatchBundleService = new ActivitySnatchBundleService;
            if ($activitySnatchBundleService->removeByIds($requestData['ids'])) {
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
