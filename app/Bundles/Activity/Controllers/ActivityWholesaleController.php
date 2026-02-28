<?php

declare(strict_types=1);

namespace App\Bundles\Activity\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Activity\Entities\ActivityWholesaleEntity;
use App\Bundles\Activity\Requests\ActivityWholesale\ActivityWholesaleCreateRequest;
use App\Bundles\Activity\Requests\ActivityWholesale\ActivityWholesaleDestroyRequest;
use App\Bundles\Activity\Requests\ActivityWholesale\ActivityWholesaleQueryRequest;
use App\Bundles\Activity\Requests\ActivityWholesale\ActivityWholesaleUpdateRequest;
use App\Bundles\Activity\Responses\ActivityWholesale\ActivityWholesaleDestroyResponse;
use App\Bundles\Activity\Responses\ActivityWholesale\ActivityWholesaleQueryResponse;
use App\Bundles\Activity\Responses\ActivityWholesale\ActivityWholesaleResponse;
use App\Bundles\Activity\Services\ActivityWholesaleBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ActivityWholesaleController extends BaseController
{
    #[OA\Post(path: '/activityWholesale/query', summary: '查询批发活动列表接口', security: [['bearerAuth' => []]], tags: ['批发活动模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityWholesaleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityWholesaleQueryResponse::class))]
    public function query(ActivityWholesaleQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ActivityWholesaleQueryRequest::getGoodsId])) {
                $condition[] = [ActivityWholesaleEntity::getGoodsId, '=', $requestData[ActivityWholesaleQueryRequest::getGoodsId]];
            }
            if (isset($requestData[ActivityWholesaleQueryRequest::getActId])) {
                $condition[] = [ActivityWholesaleEntity::getActId, '=', $requestData[ActivityWholesaleQueryRequest::getActId]];
            }

            $activityWholesaleBundleService = new ActivityWholesaleBundleService;
            $result = $activityWholesaleBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ActivityWholesaleResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ActivityWholesaleQueryResponse($result);
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

    #[OA\Post(path: '/activityWholesale/store', summary: '新增批发活动接口', security: [['bearerAuth' => []]], tags: ['批发活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityWholesaleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityWholesaleResponse::class))]
    public function store(ActivityWholesaleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ActivityWholesaleEntity($requestData);

            $activityWholesaleBundleService = new ActivityWholesaleBundleService;
            if ($activityWholesaleBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/activityWholesale/show', summary: '获取批发活动详情接口', security: [['bearerAuth' => []]], tags: ['批发活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityWholesaleResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $activityWholesaleBundleService = new ActivityWholesaleBundleService;
            $activityWholesale = $activityWholesaleBundleService->getOneById($id);
            if (empty($activityWholesale)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ActivityWholesaleResponse($activityWholesale);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/activityWholesale/update', summary: '更新批发活动接口', security: [['bearerAuth' => []]], tags: ['批发活动模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityWholesaleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityWholesaleResponse::class))]
    public function update(ActivityWholesaleUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $activityWholesaleBundleService = new ActivityWholesaleBundleService;
            $activityWholesale = $activityWholesaleBundleService->getOneById($id);
            if (empty($activityWholesale)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ActivityWholesaleEntity($requestData);

            $activityWholesaleBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/activityWholesale/destroy', summary: '删除批发活动接口', security: [['bearerAuth' => []]], tags: ['批发活动模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ActivityWholesaleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ActivityWholesaleDestroyResponse::class))]
    public function destroy(ActivityWholesaleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $activityWholesaleBundleService = new ActivityWholesaleBundleService;
            if ($activityWholesaleBundleService->removeByIds($requestData['ids'])) {
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
