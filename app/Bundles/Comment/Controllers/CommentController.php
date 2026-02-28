<?php

declare(strict_types=1);

namespace App\Bundles\Comment\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Comment\Entities\CommentEntity;
use App\Bundles\Comment\Requests\Comment\CommentCreateRequest;
use App\Bundles\Comment\Requests\Comment\CommentDestroyRequest;
use App\Bundles\Comment\Requests\Comment\CommentQueryRequest;
use App\Bundles\Comment\Requests\Comment\CommentUpdateRequest;
use App\Bundles\Comment\Responses\Comment\CommentDestroyResponse;
use App\Bundles\Comment\Responses\Comment\CommentQueryResponse;
use App\Bundles\Comment\Responses\Comment\CommentResponse;
use App\Bundles\Comment\Services\CommentBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class CommentController extends BaseController
{
    #[OA\Post(path: '/comment/query', summary: '查询评论列表接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommentQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CommentQueryResponse::class))]
    public function query(CommentQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[CommentQueryRequest::getIdValue])) {
                $condition[] = [CommentEntity::getIdValue, '=', $requestData[CommentQueryRequest::getIdValue]];
            }
            if (isset($requestData[CommentQueryRequest::getParentId])) {
                $condition[] = [CommentEntity::getParentId, '=', $requestData[CommentQueryRequest::getParentId]];
            }
            if (isset($requestData[CommentQueryRequest::getCommentId])) {
                $condition[] = [CommentEntity::getCommentId, '=', $requestData[CommentQueryRequest::getCommentId]];
            }

            $commentBundleService = new CommentBundleService;
            $result = $commentBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new CommentResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new CommentQueryResponse($result);
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

    #[OA\Post(path: '/comment/store', summary: '新增评论接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommentCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CommentResponse::class))]
    public function store(CommentCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new CommentEntity($requestData);

            $commentBundleService = new CommentBundleService;
            if ($commentBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/comment/show', summary: '获取评论详情接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CommentResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $commentBundleService = new CommentBundleService;
            $comment = $commentBundleService->getOneById($id);
            if (empty($comment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new CommentResponse($comment);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/comment/update', summary: '更新评论接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommentUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CommentResponse::class))]
    public function update(CommentUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $commentBundleService = new CommentBundleService;
            $comment = $commentBundleService->getOneById($id);
            if (empty($comment)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new CommentEntity($requestData);

            $commentBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/comment/destroy', summary: '删除评论接口', security: [['bearerAuth' => []]], tags: ['评论模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CommentDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CommentDestroyResponse::class))]
    public function destroy(CommentDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $commentBundleService = new CommentBundleService;
            if ($commentBundleService->removeByIds($requestData['ids'])) {
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
