<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Vote\Entities\VoteLogEntity;
use App\Bundles\Vote\Requests\VoteLog\VoteLogCreateRequest;
use App\Bundles\Vote\Requests\VoteLog\VoteLogDestroyRequest;
use App\Bundles\Vote\Requests\VoteLog\VoteLogQueryRequest;
use App\Bundles\Vote\Requests\VoteLog\VoteLogUpdateRequest;
use App\Bundles\Vote\Responses\VoteLog\VoteLogDestroyResponse;
use App\Bundles\Vote\Responses\VoteLog\VoteLogQueryResponse;
use App\Bundles\Vote\Responses\VoteLog\VoteLogResponse;
use App\Bundles\Vote\Services\VoteLogBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class VoteLogController extends BaseController
{
    #[OA\Post(path: '/voteLog/query', summary: '查询投票记录列表接口', security: [['bearerAuth' => []]], tags: ['投票记录模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: VoteLogQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: VoteLogQueryResponse::class))]
    public function query(VoteLogQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[VoteLogQueryRequest::getLogId])) {
                $condition[] = [VoteLogEntity::getLogId, '=', $requestData[VoteLogQueryRequest::getLogId]];
            }
            if (isset($requestData[VoteLogQueryRequest::getVoteId])) {
                $condition[] = [VoteLogEntity::getVoteId, '=', $requestData[VoteLogQueryRequest::getVoteId]];
            }

            $voteLogBundleService = new VoteLogBundleService;
            $result = $voteLogBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new VoteLogResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new VoteLogQueryResponse($result);
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

    #[OA\Post(path: '/voteLog/store', summary: '新增投票记录接口', security: [['bearerAuth' => []]], tags: ['投票记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: VoteLogCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: VoteLogResponse::class))]
    public function store(VoteLogCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new VoteLogEntity($requestData);

            $voteLogBundleService = new VoteLogBundleService;
            if ($voteLogBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/voteLog/show', summary: '获取投票记录详情接口', security: [['bearerAuth' => []]], tags: ['投票记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: VoteLogResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $voteLogBundleService = new VoteLogBundleService;
            $voteLog = $voteLogBundleService->getOneById($id);
            if (empty($voteLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new VoteLogResponse($voteLog);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/voteLog/update', summary: '更新投票记录接口', security: [['bearerAuth' => []]], tags: ['投票记录模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: VoteLogUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: VoteLogResponse::class))]
    public function update(VoteLogUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $voteLogBundleService = new VoteLogBundleService;
            $voteLog = $voteLogBundleService->getOneById($id);
            if (empty($voteLog)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new VoteLogEntity($requestData);

            $voteLogBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/voteLog/destroy', summary: '删除投票记录接口', security: [['bearerAuth' => []]], tags: ['投票记录模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: VoteLogDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: VoteLogDestroyResponse::class))]
    public function destroy(VoteLogDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $voteLogBundleService = new VoteLogBundleService;
            if ($voteLogBundleService->removeByIds($requestData['ids'])) {
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
