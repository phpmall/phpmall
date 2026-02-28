<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Ad\Entities\AdCustomEntity;
use App\Bundles\Ad\Requests\AdCustom\AdCustomCreateRequest;
use App\Bundles\Ad\Requests\AdCustom\AdCustomDestroyRequest;
use App\Bundles\Ad\Requests\AdCustom\AdCustomQueryRequest;
use App\Bundles\Ad\Requests\AdCustom\AdCustomUpdateRequest;
use App\Bundles\Ad\Responses\AdCustom\AdCustomDestroyResponse;
use App\Bundles\Ad\Responses\AdCustom\AdCustomQueryResponse;
use App\Bundles\Ad\Responses\AdCustom\AdCustomResponse;
use App\Bundles\Ad\Services\AdCustomBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdCustomController extends BaseController
{
    #[OA\Post(path: '/adCustom/query', summary: '查询自定义广告列表接口', security: [['bearerAuth' => []]], tags: ['自定义广告模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdCustomQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdCustomQueryResponse::class))]
    public function query(AdCustomQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdCustomQueryRequest::getAdId])) {
                $condition[] = [AdCustomEntity::getAdId, '=', $requestData[AdCustomQueryRequest::getAdId]];
            }

            $adCustomBundleService = new AdCustomBundleService;
            $result = $adCustomBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AdCustomResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new AdCustomQueryResponse($result);
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

    #[OA\Post(path: '/adCustom/store', summary: '新增自定义广告接口', security: [['bearerAuth' => []]], tags: ['自定义广告模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdCustomCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdCustomResponse::class))]
    public function store(AdCustomCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new AdCustomEntity($requestData);

            $adCustomBundleService = new AdCustomBundleService;
            if ($adCustomBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adCustom/show', summary: '获取自定义广告详情接口', security: [['bearerAuth' => []]], tags: ['自定义广告模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdCustomResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $adCustomBundleService = new AdCustomBundleService;
            $adCustom = $adCustomBundleService->getOneById($id);
            if (empty($adCustom)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new AdCustomResponse($adCustom);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adCustom/update', summary: '更新自定义广告接口', security: [['bearerAuth' => []]], tags: ['自定义广告模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdCustomUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdCustomResponse::class))]
    public function update(AdCustomUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adCustomBundleService = new AdCustomBundleService;
            $adCustom = $adCustomBundleService->getOneById($id);
            if (empty($adCustom)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new AdCustomEntity($requestData);

            $adCustomBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/adCustom/destroy', summary: '删除自定义广告接口', security: [['bearerAuth' => []]], tags: ['自定义广告模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdCustomDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdCustomDestroyResponse::class))]
    public function destroy(AdCustomDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $adCustomBundleService = new AdCustomBundleService;
            if ($adCustomBundleService->removeByIds($requestData['ids'])) {
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
