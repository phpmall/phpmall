<?php

declare(strict_types=1);

namespace App\Bundles\Article\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Article\Entities\ArticleCatEntity;
use App\Bundles\Article\Requests\ArticleCat\ArticleCatCreateRequest;
use App\Bundles\Article\Requests\ArticleCat\ArticleCatDestroyRequest;
use App\Bundles\Article\Requests\ArticleCat\ArticleCatQueryRequest;
use App\Bundles\Article\Requests\ArticleCat\ArticleCatUpdateRequest;
use App\Bundles\Article\Responses\ArticleCat\ArticleCatDestroyResponse;
use App\Bundles\Article\Responses\ArticleCat\ArticleCatQueryResponse;
use App\Bundles\Article\Responses\ArticleCat\ArticleCatResponse;
use App\Bundles\Article\Services\ArticleCatBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ArticleCatController extends BaseController
{
    #[OA\Post(path: '/articleCat/query', summary: '查询文章分类列表接口', security: [['bearerAuth' => []]], tags: ['文章分类模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ArticleCatQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleCatQueryResponse::class))]
    public function query(ArticleCatQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ArticleCatQueryRequest::getCatType])) {
                $condition[] = [ArticleCatEntity::getCatType, '=', $requestData[ArticleCatQueryRequest::getCatType]];
            }
            if (isset($requestData[ArticleCatQueryRequest::getParentId])) {
                $condition[] = [ArticleCatEntity::getParentId, '=', $requestData[ArticleCatQueryRequest::getParentId]];
            }
            if (isset($requestData[ArticleCatQueryRequest::getSortOrder])) {
                $condition[] = [ArticleCatEntity::getSortOrder, '=', $requestData[ArticleCatQueryRequest::getSortOrder]];
            }
            if (isset($requestData[ArticleCatQueryRequest::getCatId])) {
                $condition[] = [ArticleCatEntity::getCatId, '=', $requestData[ArticleCatQueryRequest::getCatId]];
            }

            $articleCatBundleService = new ArticleCatBundleService;
            $result = $articleCatBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ArticleCatResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ArticleCatQueryResponse($result);
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

    #[OA\Post(path: '/articleCat/store', summary: '新增文章分类接口', security: [['bearerAuth' => []]], tags: ['文章分类模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ArticleCatCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleCatResponse::class))]
    public function store(ArticleCatCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ArticleCatEntity($requestData);

            $articleCatBundleService = new ArticleCatBundleService;
            if ($articleCatBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/articleCat/show', summary: '获取文章分类详情接口', security: [['bearerAuth' => []]], tags: ['文章分类模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleCatResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $articleCatBundleService = new ArticleCatBundleService;
            $articleCat = $articleCatBundleService->getOneById($id);
            if (empty($articleCat)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ArticleCatResponse($articleCat);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/articleCat/update', summary: '更新文章分类接口', security: [['bearerAuth' => []]], tags: ['文章分类模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ArticleCatUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleCatResponse::class))]
    public function update(ArticleCatUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $articleCatBundleService = new ArticleCatBundleService;
            $articleCat = $articleCatBundleService->getOneById($id);
            if (empty($articleCat)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ArticleCatEntity($requestData);

            $articleCatBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/articleCat/destroy', summary: '删除文章分类接口', security: [['bearerAuth' => []]], tags: ['文章分类模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ArticleCatDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleCatDestroyResponse::class))]
    public function destroy(ArticleCatDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $articleCatBundleService = new ArticleCatBundleService;
            if ($articleCatBundleService->removeByIds($requestData['ids'])) {
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
