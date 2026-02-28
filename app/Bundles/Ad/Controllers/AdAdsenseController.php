<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Ad\Entities\AdAdsenseEntity;
use App\Bundles\Ad\Requests\AdAdsense\AdAdsenseCreateRequest;
use App\Bundles\Ad\Requests\AdAdsense\AdAdsenseDestroyRequest;
use App\Bundles\Ad\Requests\AdAdsense\AdAdsenseQueryRequest;
use App\Bundles\Ad\Requests\AdAdsense\AdAdsenseUpdateRequest;
use App\Bundles\Ad\Responses\AdAdsense\AdAdsenseDestroyResponse;
use App\Bundles\Ad\Responses\AdAdsense\AdAdsenseQueryResponse;
use App\Bundles\Ad\Responses\AdAdsense\AdAdsenseResponse;
use App\Bundles\Ad\Services\AdAdsenseBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdAdsenseController extends BaseController
{
    #[OA\Post(path: '/adAdsense/query', summary: '查询广告联盟列表接口', security: [['bearerAuth' => []]], tags: ['广告联盟模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdAdsenseQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdAdsenseQueryResponse::class))]
    public function query(AdAdsenseQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdAdsenseQueryRequest::getFromAd])) {
                $condition[] = [AdAdsenseEntity::getFromAd, '=', $requestData[AdAdsenseQueryRequest::getFromAd]];
            }
            if (isset($requestData[AdAdsenseQueryRequest::getId])) {
                $condition[] = [AdAdsenseEntity::getId, '=', $requestData[AdAdsenseQueryRequest::getId]];
            }

            $adAdsenseBundleService = new AdAdsenseBundleService;
            $result = $adAdsenseBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AdAdsenseResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new AdAdsenseQueryResponse($result);
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

    #[OA\Post(path: '/adAdsense/store', summary: '新增广告联盟接口', security: [['bearerAuth' => []]], tags: ['广告联盟模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdAdsenseCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdAdsenseResponse::class))]
    public function store(AdAdsenseCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new AdAdsenseEntity($requestData);

            $adAdsenseBundleService = new AdAdsenseBundleService;
            if ($adAdsenseBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adAdsense/show', summary: '获取广告联盟详情接口', security: [['bearerAuth' => []]], tags: ['广告联盟模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdAdsenseResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $adAdsenseBundleService = new AdAdsenseBundleService;
            $adAdsense = $adAdsenseBundleService->getOneById($id);
            if (empty($adAdsense)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new AdAdsenseResponse($adAdsense);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adAdsense/update', summary: '更新广告联盟接口', security: [['bearerAuth' => []]], tags: ['广告联盟模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdAdsenseUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdAdsenseResponse::class))]
    public function update(AdAdsenseUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adAdsenseBundleService = new AdAdsenseBundleService;
            $adAdsense = $adAdsenseBundleService->getOneById($id);
            if (empty($adAdsense)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new AdAdsenseEntity($requestData);

            $adAdsenseBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/adAdsense/destroy', summary: '删除广告联盟接口', security: [['bearerAuth' => []]], tags: ['广告联盟模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdAdsenseDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdAdsenseDestroyResponse::class))]
    public function destroy(AdAdsenseDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $adAdsenseBundleService = new AdAdsenseBundleService;
            if ($adAdsenseBundleService->removeByIds($requestData['ids'])) {
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
