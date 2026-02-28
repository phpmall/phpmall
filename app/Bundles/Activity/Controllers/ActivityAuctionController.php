<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Activity\Entities\ActivityAuctionEntity;
use App\Bundles\Activity\Requests\ActivityAuction\ActivityAuctionCreateRequest;
use App\Bundles\Activity\Requests\ActivityAuction\ActivityAuctionDestroyRequest;
use App\Bundles\Activity\Requests\ActivityAuction\ActivityAuctionQueryRequest;
use App\Bundles\Activity\Requests\ActivityAuction\ActivityAuctionUpdateRequest;
use App\Bundles\Activity\Responses\ActivityAuction\ActivityAuctionDestroyResponse;
use App\Bundles\Activity\Responses\ActivityAuction\ActivityAuctionQueryResponse;
use App\Bundles\Activity\Responses\ActivityAuction\ActivityAuctionResponse;
use App\Bundles\Activity\Services\ActivityAuctionBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ActivityAuctionController extends BaseController
{
    #[OA\Post(path: '/activityAuction/query', summary: '查询拍卖活动列表接口', security: [['bearerAuth' => []]], tags: ['拍卖活动模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityAuctionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityAuctionQueryResponse::class))]
    public function query(ActivityAuctionQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ActivityAuctionQueryRequest::getActId])) {
                $condition[] = [ActivityAuctionEntity::getActId, '=', $requestData[ActivityAuctionQueryRequest::getActId]];
            }
            if (isset($requestData[ActivityAuctionQueryRequest::getLogId])) {
                $condition[] = [ActivityAuctionEntity::getLogId, '=', $requestData[ActivityAuctionQueryRequest::getLogId]];
            }

            $activityAuctionBundleService = new ActivityAuctionBundleService;
            $result = $activityAuctionBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ActivityAuctionResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ActivityAuctionQueryResponse($result);
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

    #[OA\Post(path: '/activityAuction/store', summary: '新增拍卖活动接口', security: [['bearerAuth' => []]], tags: ['拍卖活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityAuctionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityAuctionResponse::class))]
    public function store(ActivityAuctionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ActivityAuctionEntity($requestData);

            $activityAuctionBundleService = new ActivityAuctionBundleService;
            if ($activityAuctionBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/activityAuction/show', summary: '获取拍卖活动详情接口', security: [['bearerAuth' => []]], tags: ['拍卖活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityAuctionResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $activityAuctionBundleService = new ActivityAuctionBundleService;
            $activityAuction = $activityAuctionBundleService->getOneById($id);
            if (empty($activityAuction)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ActivityAuctionResponse($activityAuction);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/activityAuction/update', summary: '更新拍卖活动接口', security: [['bearerAuth' => []]], tags: ['拍卖活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityAuctionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityAuctionResponse::class))]
    public function update(ActivityAuctionUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $activityAuctionBundleService = new ActivityAuctionBundleService;
            $activityAuction = $activityAuctionBundleService->getOneById($id);
            if (empty($activityAuction)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ActivityAuctionEntity($requestData);

            $activityAuctionBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/activityAuction/destroy', summary: '删除拍卖活动接口', security: [['bearerAuth' => []]], tags: ['拍卖活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityAuctionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityAuctionDestroyResponse::class))]
    public function destroy(ActivityAuctionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $activityAuctionBundleService = new ActivityAuctionBundleService;
            if ($activityAuctionBundleService->removeByIds($requestData['ids'])) {
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
