<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Activity\Entities\ActivityTopicEntity;
use App\Bundles\Activity\Requests\ActivityTopic\ActivityTopicCreateRequest;
use App\Bundles\Activity\Requests\ActivityTopic\ActivityTopicDestroyRequest;
use App\Bundles\Activity\Requests\ActivityTopic\ActivityTopicQueryRequest;
use App\Bundles\Activity\Requests\ActivityTopic\ActivityTopicUpdateRequest;
use App\Bundles\Activity\Responses\ActivityTopic\ActivityTopicDestroyResponse;
use App\Bundles\Activity\Responses\ActivityTopic\ActivityTopicQueryResponse;
use App\Bundles\Activity\Responses\ActivityTopic\ActivityTopicResponse;
use App\Bundles\Activity\Services\ActivityTopicBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ActivityTopicController extends BaseController
{
    #[OA\Post(path: '/activityTopic/query', summary: '查询专题活动列表接口', security: [['bearerAuth' => []]], tags: ['专题活动模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityTopicQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityTopicQueryResponse::class))]
    public function query(ActivityTopicQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ActivityTopicQueryRequest::getTopicId])) {
                $condition[] = [ActivityTopicEntity::getTopicId, '=', $requestData[ActivityTopicQueryRequest::getTopicId]];
            }

            $activityTopicBundleService = new ActivityTopicBundleService;
            $result = $activityTopicBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ActivityTopicResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ActivityTopicQueryResponse($result);
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

    #[OA\Post(path: '/activityTopic/store', summary: '新增专题活动接口', security: [['bearerAuth' => []]], tags: ['专题活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityTopicCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityTopicResponse::class))]
    public function store(ActivityTopicCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ActivityTopicEntity($requestData);

            $activityTopicBundleService = new ActivityTopicBundleService;
            if ($activityTopicBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/activityTopic/show', summary: '获取专题活动详情接口', security: [['bearerAuth' => []]], tags: ['专题活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityTopicResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $activityTopicBundleService = new ActivityTopicBundleService;
            $activityTopic = $activityTopicBundleService->getOneById($id);
            if (empty($activityTopic)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ActivityTopicResponse($activityTopic);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/activityTopic/update', summary: '更新专题活动接口', security: [['bearerAuth' => []]], tags: ['专题活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityTopicUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityTopicResponse::class))]
    public function update(ActivityTopicUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $activityTopicBundleService = new ActivityTopicBundleService;
            $activityTopic = $activityTopicBundleService->getOneById($id);
            if (empty($activityTopic)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ActivityTopicEntity($requestData);

            $activityTopicBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/activityTopic/destroy', summary: '删除专题活动接口', security: [['bearerAuth' => []]], tags: ['专题活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityTopicDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityTopicDestroyResponse::class))]
    public function destroy(ActivityTopicDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $activityTopicBundleService = new ActivityTopicBundleService;
            if ($activityTopicBundleService->removeByIds($requestData['ids'])) {
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
