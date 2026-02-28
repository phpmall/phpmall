<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Activity\Entities\ActivityExchangeEntity;
use App\Bundles\Activity\Requests\ActivityExchange\ActivityExchangeCreateRequest;
use App\Bundles\Activity\Requests\ActivityExchange\ActivityExchangeDestroyRequest;
use App\Bundles\Activity\Requests\ActivityExchange\ActivityExchangeQueryRequest;
use App\Bundles\Activity\Requests\ActivityExchange\ActivityExchangeUpdateRequest;
use App\Bundles\Activity\Responses\ActivityExchange\ActivityExchangeDestroyResponse;
use App\Bundles\Activity\Responses\ActivityExchange\ActivityExchangeQueryResponse;
use App\Bundles\Activity\Responses\ActivityExchange\ActivityExchangeResponse;
use App\Bundles\Activity\Services\ActivityExchangeBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ActivityExchangeController extends BaseController
{
    #[OA\Post(path: '/activityExchange/query', summary: '查询积分兑换活动列表接口', security: [['bearerAuth' => []]], tags: ['积分兑换活动模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityExchangeQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityExchangeQueryResponse::class))]
    public function query(ActivityExchangeQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ActivityExchangeQueryRequest::getGoodsId])) {
                $condition[] = [ActivityExchangeEntity::getGoodsId, '=', $requestData[ActivityExchangeQueryRequest::getGoodsId]];
            }
            if (isset($requestData[ActivityExchangeQueryRequest::getId])) {
                $condition[] = [ActivityExchangeEntity::getId, '=', $requestData[ActivityExchangeQueryRequest::getId]];
            }

            $activityExchangeBundleService = new ActivityExchangeBundleService;
            $result = $activityExchangeBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ActivityExchangeResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ActivityExchangeQueryResponse($result);
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

    #[OA\Post(path: '/activityExchange/store', summary: '新增积分兑换活动接口', security: [['bearerAuth' => []]], tags: ['积分兑换活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityExchangeCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityExchangeResponse::class))]
    public function store(ActivityExchangeCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ActivityExchangeEntity($requestData);

            $activityExchangeBundleService = new ActivityExchangeBundleService;
            if ($activityExchangeBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/activityExchange/show', summary: '获取积分兑换活动详情接口', security: [['bearerAuth' => []]], tags: ['积分兑换活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityExchangeResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $activityExchangeBundleService = new ActivityExchangeBundleService;
            $activityExchange = $activityExchangeBundleService->getOneById($id);
            if (empty($activityExchange)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ActivityExchangeResponse($activityExchange);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/activityExchange/update', summary: '更新积分兑换活动接口', security: [['bearerAuth' => []]], tags: ['积分兑换活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityExchangeUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityExchangeResponse::class))]
    public function update(ActivityExchangeUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $activityExchangeBundleService = new ActivityExchangeBundleService;
            $activityExchange = $activityExchangeBundleService->getOneById($id);
            if (empty($activityExchange)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ActivityExchangeEntity($requestData);

            $activityExchangeBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/activityExchange/destroy', summary: '删除积分兑换活动接口', security: [['bearerAuth' => []]], tags: ['积分兑换活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityExchangeDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityExchangeDestroyResponse::class))]
    public function destroy(ActivityExchangeDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $activityExchangeBundleService = new ActivityExchangeBundleService;
            if ($activityExchangeBundleService->removeByIds($requestData['ids'])) {
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
