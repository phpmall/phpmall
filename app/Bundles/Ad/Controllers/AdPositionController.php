<?php

declare(strict_types=1);

namespace App\Bundles\Ad\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Ad\Entities\AdPositionEntity;
use App\Bundles\Ad\Requests\AdPosition\AdPositionCreateRequest;
use App\Bundles\Ad\Requests\AdPosition\AdPositionDestroyRequest;
use App\Bundles\Ad\Requests\AdPosition\AdPositionQueryRequest;
use App\Bundles\Ad\Requests\AdPosition\AdPositionUpdateRequest;
use App\Bundles\Ad\Responses\AdPosition\AdPositionDestroyResponse;
use App\Bundles\Ad\Responses\AdPosition\AdPositionQueryResponse;
use App\Bundles\Ad\Responses\AdPosition\AdPositionResponse;
use App\Bundles\Ad\Services\AdPositionBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class AdPositionController extends BaseController
{
    #[OA\Post(path: '/adPosition/query', summary: '查询广告位置列表接口', security: [['bearerAuth' => []]], tags: ['广告位置模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdPositionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdPositionQueryResponse::class))]
    public function query(AdPositionQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[AdPositionQueryRequest::getPositionId])) {
                $condition[] = [AdPositionEntity::getPositionId, '=', $requestData[AdPositionQueryRequest::getPositionId]];
            }

            $adPositionBundleService = new AdPositionBundleService;
            $result = $adPositionBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AdPositionResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new AdPositionQueryResponse($result);
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

    #[OA\Post(path: '/adPosition/store', summary: '新增广告位置接口', security: [['bearerAuth' => []]], tags: ['广告位置模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdPositionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdPositionResponse::class))]
    public function store(AdPositionCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new AdPositionEntity($requestData);

            $adPositionBundleService = new AdPositionBundleService;
            if ($adPositionBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/adPosition/show', summary: '获取广告位置详情接口', security: [['bearerAuth' => []]], tags: ['广告位置模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdPositionResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $adPositionBundleService = new AdPositionBundleService;
            $adPosition = $adPositionBundleService->getOneById($id);
            if (empty($adPosition)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new AdPositionResponse($adPosition);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/adPosition/update', summary: '更新广告位置接口', security: [['bearerAuth' => []]], tags: ['广告位置模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdPositionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdPositionResponse::class))]
    public function update(AdPositionUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $adPositionBundleService = new AdPositionBundleService;
            $adPosition = $adPositionBundleService->getOneById($id);
            if (empty($adPosition)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new AdPositionEntity($requestData);

            $adPositionBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/adPosition/destroy', summary: '删除广告位置接口', security: [['bearerAuth' => []]], tags: ['广告位置模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AdPositionDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AdPositionDestroyResponse::class))]
    public function destroy(AdPositionDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $adPositionBundleService = new AdPositionBundleService;
            if ($adPositionBundleService->removeByIds($requestData['ids'])) {
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
