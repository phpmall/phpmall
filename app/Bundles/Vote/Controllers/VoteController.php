<?php

declare(strict_types=1);

namespace App\Bundles\Vote\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Vote\Entities\VoteEntity;
use App\Bundles\Vote\Requests\Vote\VoteCreateRequest;
use App\Bundles\Vote\Requests\Vote\VoteDestroyRequest;
use App\Bundles\Vote\Requests\Vote\VoteQueryRequest;
use App\Bundles\Vote\Requests\Vote\VoteUpdateRequest;
use App\Bundles\Vote\Responses\Vote\VoteDestroyResponse;
use App\Bundles\Vote\Responses\Vote\VoteQueryResponse;
use App\Bundles\Vote\Responses\Vote\VoteResponse;
use App\Bundles\Vote\Services\VoteBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class VoteController extends BaseController
{
    #[OA\Post(path: '/vote/query', summary: '查询投票列表接口', security: [['bearerAuth' => []]], tags: ['投票模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: VoteQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: VoteQueryResponse::class))]
    public function query(VoteQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[VoteQueryRequest::getVoteId])) {
                $condition[] = [VoteEntity::getVoteId, '=', $requestData[VoteQueryRequest::getVoteId]];
            }

            $voteBundleService = new VoteBundleService;
            $result = $voteBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new VoteResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new VoteQueryResponse($result);
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

    #[OA\Post(path: '/vote/store', summary: '新增投票接口', security: [['bearerAuth' => []]], tags: ['投票模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: VoteCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: VoteResponse::class))]
    public function store(VoteCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new VoteEntity($requestData);

            $voteBundleService = new VoteBundleService;
            if ($voteBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/vote/show', summary: '获取投票详情接口', security: [['bearerAuth' => []]], tags: ['投票模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: VoteResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $voteBundleService = new VoteBundleService;
            $vote = $voteBundleService->getOneById($id);
            if (empty($vote)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new VoteResponse($vote);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/vote/update', summary: '更新投票接口', security: [['bearerAuth' => []]], tags: ['投票模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: VoteUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: VoteResponse::class))]
    public function update(VoteUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $voteBundleService = new VoteBundleService;
            $vote = $voteBundleService->getOneById($id);
            if (empty($vote)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new VoteEntity($requestData);

            $voteBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/vote/destroy', summary: '删除投票接口', security: [['bearerAuth' => []]], tags: ['投票模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: VoteDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: VoteDestroyResponse::class))]
    public function destroy(VoteDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $voteBundleService = new VoteBundleService;
            if ($voteBundleService->removeByIds($requestData['ids'])) {
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
