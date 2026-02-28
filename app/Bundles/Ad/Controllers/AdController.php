<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Ad\Entities\AdEntity;
use App\Bundles\Ad\Requests\Ad\AdCreateRequest;
use App\Bundles\Ad\Requests\Ad\AdDestroyRequest;
use App\Bundles\Ad\Requests\Ad\AdQueryRequest;
use App\Bundles\Ad\Requests\Ad\AdUpdateRequest;
use App\Bundles\Ad\Responses\Ad\AdDestroyResponse;
use App\Bundles\Ad\Responses\Ad\AdQueryResponse;
use App\Bundles\Ad\Responses\Ad\AdResponse;
use App\Bundles\Ad\Services\AdBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdController extends BaseController
{
    #[OA\Post(path: '/ad/query', summary: '查询广告列表接口', security: [['bearerAuth' => []]], tags: ['广告模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdQueryResponse::class))]
    public function query(AdQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdQueryRequest::getEnabled])) {
                $condition[] = [AdEntity::getEnabled, '=', $requestData[AdQueryRequest::getEnabled]];
            }
            if (isset($requestData[AdQueryRequest::getPositionId])) {
                $condition[] = [AdEntity::getPositionId, '=', $requestData[AdQueryRequest::getPositionId]];
            }
            if (isset($requestData[AdQueryRequest::getAdId])) {
                $condition[] = [AdEntity::getAdId, '=', $requestData[AdQueryRequest::getAdId]];
            }

            $adBundleService = new AdBundleService;
            $result = $adBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AdResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new AdQueryResponse($result);
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

    #[OA\Post(path: '/ad/store', summary: '新增广告接口', security: [['bearerAuth' => []]], tags: ['广告模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdResponse::class))]
    public function store(AdCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new AdEntity($requestData);

            $adBundleService = new AdBundleService;
            if ($adBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/ad/show', summary: '获取广告详情接口', security: [['bearerAuth' => []]], tags: ['广告模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $adBundleService = new AdBundleService;
            $ad = $adBundleService->getOneById($id);
            if (empty($ad)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new AdResponse($ad);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/ad/update', summary: '更新广告接口', security: [['bearerAuth' => []]], tags: ['广告模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdResponse::class))]
    public function update(AdUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adBundleService = new AdBundleService;
            $ad = $adBundleService->getOneById($id);
            if (empty($ad)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new AdEntity($requestData);

            $adBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/ad/destroy', summary: '删除广告接口', security: [['bearerAuth' => []]], tags: ['广告模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdDestroyResponse::class))]
    public function destroy(AdDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $adBundleService = new AdBundleService;
            if ($adBundleService->removeByIds($requestData['ids'])) {
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
