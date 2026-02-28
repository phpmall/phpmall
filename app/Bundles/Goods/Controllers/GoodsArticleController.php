<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsArticleEntity;
use App\Bundles\Goods\Requests\GoodsArticle\GoodsArticleCreateRequest;
use App\Bundles\Goods\Requests\GoodsArticle\GoodsArticleDestroyRequest;
use App\Bundles\Goods\Requests\GoodsArticle\GoodsArticleQueryRequest;
use App\Bundles\Goods\Requests\GoodsArticle\GoodsArticleUpdateRequest;
use App\Bundles\Goods\Responses\GoodsArticle\GoodsArticleDestroyResponse;
use App\Bundles\Goods\Responses\GoodsArticle\GoodsArticleQueryResponse;
use App\Bundles\Goods\Responses\GoodsArticle\GoodsArticleResponse;
use App\Bundles\Goods\Services\GoodsArticleBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsArticleController extends BaseController
{
    #[OA\Post(path: '/goodsArticle/query', summary: '查询商品文章关联列表接口', security: [['bearerAuth' => []]], tags: ['商品文章关联模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsArticleQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsArticleQueryResponse::class))]
    public function query(GoodsArticleQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsArticleQueryRequest::getArticleId])) {
                $condition[] = [GoodsArticleEntity::getArticleId, '=', $requestData[GoodsArticleQueryRequest::getArticleId]];
            }
            if (isset($requestData[GoodsArticleQueryRequest::getId])) {
                $condition[] = [GoodsArticleEntity::getId, '=', $requestData[GoodsArticleQueryRequest::getId]];
            }

            $goodsArticleBundleService = new GoodsArticleBundleService;
            $result = $goodsArticleBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsArticleResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsArticleQueryResponse($result);
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

    #[OA\Post(path: '/goodsArticle/store', summary: '新增商品文章关联接口', security: [['bearerAuth' => []]], tags: ['商品文章关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsArticleCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsArticleResponse::class))]
    public function store(GoodsArticleCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsArticleEntity($requestData);

            $goodsArticleBundleService = new GoodsArticleBundleService;
            if ($goodsArticleBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsArticle/show', summary: '获取商品文章关联详情接口', security: [['bearerAuth' => []]], tags: ['商品文章关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsArticleResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsArticleBundleService = new GoodsArticleBundleService;
            $goodsArticle = $goodsArticleBundleService->getOneById($id);
            if (empty($goodsArticle)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsArticleResponse($goodsArticle);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsArticle/update', summary: '更新商品文章关联接口', security: [['bearerAuth' => []]], tags: ['商品文章关联模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsArticleUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsArticleResponse::class))]
    public function update(GoodsArticleUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsArticleBundleService = new GoodsArticleBundleService;
            $goodsArticle = $goodsArticleBundleService->getOneById($id);
            if (empty($goodsArticle)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsArticleEntity($requestData);

            $goodsArticleBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsArticle/destroy', summary: '删除商品文章关联接口', security: [['bearerAuth' => []]], tags: ['商品文章关联模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsArticleDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsArticleDestroyResponse::class))]
    public function destroy(GoodsArticleDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsArticleBundleService = new GoodsArticleBundleService;
            if ($goodsArticleBundleService->removeByIds($requestData['ids'])) {
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
