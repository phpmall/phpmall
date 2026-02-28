<?php

declare(strict_types=1);

namespace App\Bundles\Article\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Article\Entities\ArticleEntity;
use App\Bundles\Article\Requests\Article\ArticleCreateRequest;
use App\Bundles\Article\Requests\Article\ArticleDestroyRequest;
use App\Bundles\Article\Requests\Article\ArticleQueryRequest;
use App\Bundles\Article\Requests\Article\ArticleUpdateRequest;
use App\Bundles\Article\Responses\Article\ArticleDestroyResponse;
use App\Bundles\Article\Responses\Article\ArticleQueryResponse;
use App\Bundles\Article\Responses\Article\ArticleResponse;
use App\Bundles\Article\Services\ArticleBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class ArticleController extends BaseController
{
    #[OA\Post(path: '/article/query', summary: '查询文章列表接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ArticleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleQueryResponse::class))]
    public function query(ArticleQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[ArticleQueryRequest::getCatId])) {
                $condition[] = [ArticleEntity::getCatId, '=', $requestData[ArticleQueryRequest::getCatId]];
            }
            if (isset($requestData[ArticleQueryRequest::getArticleId])) {
                $condition[] = [ArticleEntity::getArticleId, '=', $requestData[ArticleQueryRequest::getArticleId]];
            }

            $articleBundleService = new ArticleBundleService;
            $result = $articleBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new ArticleResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new ArticleQueryResponse($result);
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

    #[OA\Post(path: '/article/store', summary: '新增文章接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ArticleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleResponse::class))]
    public function store(ArticleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new ArticleEntity($requestData);

            $articleBundleService = new ArticleBundleService;
            if ($articleBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/article/show', summary: '获取文章详情接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $articleBundleService = new ArticleBundleService;
            $article = $articleBundleService->getOneById($id);
            if (empty($article)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new ArticleResponse($article);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/article/update', summary: '更新文章接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ArticleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleResponse::class))]
    public function update(ArticleUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $articleBundleService = new ArticleBundleService;
            $article = $articleBundleService->getOneById($id);
            if (empty($article)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new ArticleEntity($requestData);

            $articleBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/article/destroy', summary: '删除文章接口', security: [['bearerAuth' => []]], tags: ['文章模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ArticleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ArticleDestroyResponse::class))]
    public function destroy(ArticleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $articleBundleService = new ArticleBundleService;
            if ($articleBundleService->removeByIds($requestData['ids'])) {
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
