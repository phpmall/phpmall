<?php

declare(strict_types=1);

namespace App\Bundles\Search\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Search\Entities\SearchKeywordsEntity;
use App\Bundles\Search\Requests\SearchKeywords\SearchKeywordsCreateRequest;
use App\Bundles\Search\Requests\SearchKeywords\SearchKeywordsDestroyRequest;
use App\Bundles\Search\Requests\SearchKeywords\SearchKeywordsQueryRequest;
use App\Bundles\Search\Requests\SearchKeywords\SearchKeywordsUpdateRequest;
use App\Bundles\Search\Responses\SearchKeywords\SearchKeywordsDestroyResponse;
use App\Bundles\Search\Responses\SearchKeywords\SearchKeywordsQueryResponse;
use App\Bundles\Search\Responses\SearchKeywords\SearchKeywordsResponse;
use App\Bundles\Search\Services\SearchKeywordsBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SearchKeywordsController extends BaseController
{
    #[OA\Post(path: '/searchKeywords/query', summary: '查询搜索关键词列表接口', security: [['bearerAuth' => []]], tags: ['搜索关键词模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SearchKeywordsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchKeywordsQueryResponse::class))]
    public function query(SearchKeywordsQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SearchKeywordsQueryRequest::getKeywords])) {
                $condition[] = [SearchKeywordsEntity::getKeywords, '=', $requestData[SearchKeywordsQueryRequest::getKeywords]];
            }
            if (isset($requestData[SearchKeywordsQueryRequest::getId])) {
                $condition[] = [SearchKeywordsEntity::getId, '=', $requestData[SearchKeywordsQueryRequest::getId]];
            }

            $searchKeywordsBundleService = new SearchKeywordsBundleService;
            $result = $searchKeywordsBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new SearchKeywordsResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new SearchKeywordsQueryResponse($result);
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

    #[OA\Post(path: '/searchKeywords/store', summary: '新增搜索关键词接口', security: [['bearerAuth' => []]], tags: ['搜索关键词模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SearchKeywordsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchKeywordsResponse::class))]
    public function store(SearchKeywordsCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new SearchKeywordsEntity($requestData);

            $searchKeywordsBundleService = new SearchKeywordsBundleService;
            if ($searchKeywordsBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/searchKeywords/show', summary: '获取搜索关键词详情接口', security: [['bearerAuth' => []]], tags: ['搜索关键词模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchKeywordsResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $searchKeywordsBundleService = new SearchKeywordsBundleService;
            $searchKeywords = $searchKeywordsBundleService->getOneById($id);
            if (empty($searchKeywords)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new SearchKeywordsResponse($searchKeywords);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/searchKeywords/update', summary: '更新搜索关键词接口', security: [['bearerAuth' => []]], tags: ['搜索关键词模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SearchKeywordsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchKeywordsResponse::class))]
    public function update(SearchKeywordsUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $searchKeywordsBundleService = new SearchKeywordsBundleService;
            $searchKeywords = $searchKeywordsBundleService->getOneById($id);
            if (empty($searchKeywords)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new SearchKeywordsEntity($requestData);

            $searchKeywordsBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/searchKeywords/destroy', summary: '删除搜索关键词接口', security: [['bearerAuth' => []]], tags: ['搜索关键词模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SearchKeywordsDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchKeywordsDestroyResponse::class))]
    public function destroy(SearchKeywordsDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $searchKeywordsBundleService = new SearchKeywordsBundleService;
            if ($searchKeywordsBundleService->removeByIds($requestData['ids'])) {
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
