<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Activity\Entities\ActivityEntity;
use App\Bundles\Activity\Requests\Activity\ActivityCreateRequest;
use App\Bundles\Activity\Requests\Activity\ActivityDestroyRequest;
use App\Bundles\Activity\Requests\Activity\ActivityQueryRequest;
use App\Bundles\Activity\Requests\Activity\ActivityUpdateRequest;
use App\Bundles\Activity\Responses\Activity\ActivityDestroyResponse;
use App\Bundles\Activity\Responses\Activity\ActivityQueryResponse;
use App\Bundles\Activity\Responses\Activity\ActivityResponse;
use App\Bundles\Activity\Services\ActivityBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ActivityController extends BaseController
{
    #[OA\Post(path: '/activity/query', summary: '查询促销活动列表接口', security: [['bearerAuth' => []]], tags: ['促销活动模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityQueryResponse::class))]
    public function query(ActivityQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ActivityQueryRequest::getActName])) {
                $condition[] = [ActivityEntity::getActName, '=', $requestData[ActivityQueryRequest::getActName]];
            }
            if (isset($requestData[ActivityQueryRequest::getActId])) {
                $condition[] = [ActivityEntity::getActId, '=', $requestData[ActivityQueryRequest::getActId]];
            }

            $activityBundleService = new ActivityBundleService;
            $result = $activityBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ActivityResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ActivityQueryResponse($result);
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

    #[OA\Post(path: '/activity/store', summary: '新增促销活动接口', security: [['bearerAuth' => []]], tags: ['促销活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityResponse::class))]
    public function store(ActivityCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ActivityEntity($requestData);

            $activityBundleService = new ActivityBundleService;
            if ($activityBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/activity/show', summary: '获取促销活动详情接口', security: [['bearerAuth' => []]], tags: ['促销活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $activityBundleService = new ActivityBundleService;
            $activity = $activityBundleService->getOneById($id);
            if (empty($activity)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ActivityResponse($activity);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/activity/update', summary: '更新促销活动接口', security: [['bearerAuth' => []]], tags: ['促销活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityResponse::class))]
    public function update(ActivityUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $activityBundleService = new ActivityBundleService;
            $activity = $activityBundleService->getOneById($id);
            if (empty($activity)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ActivityEntity($requestData);

            $activityBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/activity/destroy', summary: '删除促销活动接口', security: [['bearerAuth' => []]], tags: ['促销活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityDestroyResponse::class))]
    public function destroy(ActivityDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $activityBundleService = new ActivityBundleService;
            if ($activityBundleService->removeByIds($requestData['ids'])) {
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
