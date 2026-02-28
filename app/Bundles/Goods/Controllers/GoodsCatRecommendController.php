<?php

declare(strict_types=1);

namespace App\Bundles\Goods\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\Goods\Entities\GoodsCatRecommendEntity;
use App\Bundles\Goods\Requests\GoodsCatRecommend\GoodsCatRecommendCreateRequest;
use App\Bundles\Goods\Requests\GoodsCatRecommend\GoodsCatRecommendDestroyRequest;
use App\Bundles\Goods\Requests\GoodsCatRecommend\GoodsCatRecommendQueryRequest;
use App\Bundles\Goods\Requests\GoodsCatRecommend\GoodsCatRecommendUpdateRequest;
use App\Bundles\Goods\Responses\GoodsCatRecommend\GoodsCatRecommendDestroyResponse;
use App\Bundles\Goods\Responses\GoodsCatRecommend\GoodsCatRecommendQueryResponse;
use App\Bundles\Goods\Responses\GoodsCatRecommend\GoodsCatRecommendResponse;
use App\Bundles\Goods\Services\GoodsCatRecommendBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class GoodsCatRecommendController extends BaseController
{
    #[OA\Post(path: '/goodsCatRecommend/query', summary: '查询商品分类推荐列表接口', security: [['bearerAuth' => []]], tags: ['商品分类推荐模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCatRecommendQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCatRecommendQueryResponse::class))]
    public function query(GoodsCatRecommendQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[GoodsCatRecommendQueryRequest::getRecommendType])) {
                $condition[] = [GoodsCatRecommendEntity::getRecommendType, '=', $requestData[GoodsCatRecommendQueryRequest::getRecommendType]];
            }
            if (isset($requestData[GoodsCatRecommendQueryRequest::getId])) {
                $condition[] = [GoodsCatRecommendEntity::getId, '=', $requestData[GoodsCatRecommendQueryRequest::getId]];
            }

            $goodsCatRecommendBundleService = new GoodsCatRecommendBundleService;
            $result = $goodsCatRecommendBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new GoodsCatRecommendResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new GoodsCatRecommendQueryResponse($result);
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

    #[OA\Post(path: '/goodsCatRecommend/store', summary: '新增商品分类推荐接口', security: [['bearerAuth' => []]], tags: ['商品分类推荐模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCatRecommendCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCatRecommendResponse::class))]
    public function store(GoodsCatRecommendCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new GoodsCatRecommendEntity($requestData);

            $goodsCatRecommendBundleService = new GoodsCatRecommendBundleService;
            if ($goodsCatRecommendBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/goodsCatRecommend/show', summary: '获取商品分类推荐详情接口', security: [['bearerAuth' => []]], tags: ['商品分类推荐模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCatRecommendResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $goodsCatRecommendBundleService = new GoodsCatRecommendBundleService;
            $goodsCatRecommend = $goodsCatRecommendBundleService->getOneById($id);
            if (empty($goodsCatRecommend)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new GoodsCatRecommendResponse($goodsCatRecommend);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/goodsCatRecommend/update', summary: '更新商品分类推荐接口', security: [['bearerAuth' => []]], tags: ['商品分类推荐模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCatRecommendUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCatRecommendResponse::class))]
    public function update(GoodsCatRecommendUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $goodsCatRecommendBundleService = new GoodsCatRecommendBundleService;
            $goodsCatRecommend = $goodsCatRecommendBundleService->getOneById($id);
            if (empty($goodsCatRecommend)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new GoodsCatRecommendEntity($requestData);

            $goodsCatRecommendBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/goodsCatRecommend/destroy', summary: '删除商品分类推荐接口', security: [['bearerAuth' => []]], tags: ['商品分类推荐模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: GoodsCatRecommendDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: GoodsCatRecommendDestroyResponse::class))]
    public function destroy(GoodsCatRecommendDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $goodsCatRecommendBundleService = new GoodsCatRecommendBundleService;
            if ($goodsCatRecommendBundleService->removeByIds($requestData['ids'])) {
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
