<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Activity\Entities\ActivityBonusEntity;
use App\Bundles\Activity\Requests\ActivityBonus\ActivityBonusCreateRequest;
use App\Bundles\Activity\Requests\ActivityBonus\ActivityBonusDestroyRequest;
use App\Bundles\Activity\Requests\ActivityBonus\ActivityBonusQueryRequest;
use App\Bundles\Activity\Requests\ActivityBonus\ActivityBonusUpdateRequest;
use App\Bundles\Activity\Responses\ActivityBonus\ActivityBonusDestroyResponse;
use App\Bundles\Activity\Responses\ActivityBonus\ActivityBonusQueryResponse;
use App\Bundles\Activity\Responses\ActivityBonus\ActivityBonusResponse;
use App\Bundles\Activity\Services\ActivityBonusBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ActivityBonusController extends BaseController
{
    #[OA\Post(path: '/activityBonus/query', summary: '查询红包活动列表接口', security: [['bearerAuth' => []]], tags: ['红包活动模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityBonusQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityBonusQueryResponse::class))]
    public function query(ActivityBonusQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ActivityBonusQueryRequest::getTypeId])) {
                $condition[] = [ActivityBonusEntity::getTypeId, '=', $requestData[ActivityBonusQueryRequest::getTypeId]];
            }

            $activityBonusBundleService = new ActivityBonusBundleService;
            $result = $activityBonusBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ActivityBonusResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ActivityBonusQueryResponse($result);
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

    #[OA\Post(path: '/activityBonus/store', summary: '新增红包活动接口', security: [['bearerAuth' => []]], tags: ['红包活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityBonusCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityBonusResponse::class))]
    public function store(ActivityBonusCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ActivityBonusEntity($requestData);

            $activityBonusBundleService = new ActivityBonusBundleService;
            if ($activityBonusBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/activityBonus/show', summary: '获取红包活动详情接口', security: [['bearerAuth' => []]], tags: ['红包活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityBonusResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $activityBonusBundleService = new ActivityBonusBundleService;
            $activityBonus = $activityBonusBundleService->getOneById($id);
            if (empty($activityBonus)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ActivityBonusResponse($activityBonus);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/activityBonus/update', summary: '更新红包活动接口', security: [['bearerAuth' => []]], tags: ['红包活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityBonusUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityBonusResponse::class))]
    public function update(ActivityBonusUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $activityBonusBundleService = new ActivityBonusBundleService;
            $activityBonus = $activityBonusBundleService->getOneById($id);
            if (empty($activityBonus)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ActivityBonusEntity($requestData);

            $activityBonusBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/activityBonus/destroy', summary: '删除红包活动接口', security: [['bearerAuth' => []]], tags: ['红包活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityBonusDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityBonusDestroyResponse::class))]
    public function destroy(ActivityBonusDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $activityBonusBundleService = new ActivityBonusBundleService;
            if ($activityBonusBundleService->removeByIds($requestData['ids'])) {
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
