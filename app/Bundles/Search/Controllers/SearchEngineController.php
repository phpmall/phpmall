<?php

declare(strict_types=1);

namespace App\Bundles\Search\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Search\Entities\SearchEngineEntity;
use App\Bundles\Search\Requests\SearchEngine\SearchEngineCreateRequest;
use App\Bundles\Search\Requests\SearchEngine\SearchEngineDestroyRequest;
use App\Bundles\Search\Requests\SearchEngine\SearchEngineQueryRequest;
use App\Bundles\Search\Requests\SearchEngine\SearchEngineUpdateRequest;
use App\Bundles\Search\Responses\SearchEngine\SearchEngineDestroyResponse;
use App\Bundles\Search\Responses\SearchEngine\SearchEngineQueryResponse;
use App\Bundles\Search\Responses\SearchEngine\SearchEngineResponse;
use App\Bundles\Search\Services\SearchEngineBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class SearchEngineController extends BaseController
{
    #[OA\Post(path: '/searchEngine/query', summary: '查询搜索引擎列表接口', security: [['bearerAuth' => []]], tags: ['搜索引擎模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SearchEngineQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchEngineQueryResponse::class))]
    public function query(SearchEngineQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[SearchEngineQueryRequest::getSearchEngine])) {
                $condition[] = [SearchEngineEntity::getSearchEngine, '=', $requestData[SearchEngineQueryRequest::getSearchEngine]];
            }
            if (isset($requestData[SearchEngineQueryRequest::getId])) {
                $condition[] = [SearchEngineEntity::getId, '=', $requestData[SearchEngineQueryRequest::getId]];
            }

            $searchEngineBundleService = new SearchEngineBundleService;
            $result = $searchEngineBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new SearchEngineResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new SearchEngineQueryResponse($result);
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

    #[OA\Post(path: '/searchEngine/store', summary: '新增搜索引擎接口', security: [['bearerAuth' => []]], tags: ['搜索引擎模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SearchEngineCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchEngineResponse::class))]
    public function store(SearchEngineCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new SearchEngineEntity($requestData);

            $searchEngineBundleService = new SearchEngineBundleService;
            if ($searchEngineBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/searchEngine/show', summary: '获取搜索引擎详情接口', security: [['bearerAuth' => []]], tags: ['搜索引擎模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchEngineResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $searchEngineBundleService = new SearchEngineBundleService;
            $searchEngine = $searchEngineBundleService->getOneById($id);
            if (empty($searchEngine)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new SearchEngineResponse($searchEngine);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/searchEngine/update', summary: '更新搜索引擎接口', security: [['bearerAuth' => []]], tags: ['搜索引擎模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SearchEngineUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchEngineResponse::class))]
    public function update(SearchEngineUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $searchEngineBundleService = new SearchEngineBundleService;
            $searchEngine = $searchEngineBundleService->getOneById($id);
            if (empty($searchEngine)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new SearchEngineEntity($requestData);

            $searchEngineBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/searchEngine/destroy', summary: '删除搜索引擎接口', security: [['bearerAuth' => []]], tags: ['搜索引擎模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SearchEngineDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SearchEngineDestroyResponse::class))]
    public function destroy(SearchEngineDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $searchEngineBundleService = new SearchEngineBundleService;
            if ($searchEngineBundleService->removeByIds($requestData['ids'])) {
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
