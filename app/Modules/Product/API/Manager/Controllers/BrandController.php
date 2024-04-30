<?php

declare(strict_types=1);

namespace App\Modules\Product\API\Manager\Controllers;

use App\API\Manager\Controllers\BaseController;
use App\Modules\Product\API\Manager\Requests\Brand\BrandCreateRequest;
use App\Modules\Product\API\Manager\Requests\Brand\BrandQueryRequest;
use App\Modules\Product\API\Manager\Requests\Brand\BrandUpdateRequest;
use App\Modules\Product\API\Manager\Responses\Brand\BrandDestroyResponse;
use App\Modules\Product\API\Manager\Responses\Brand\BrandQueryResponse;
use App\Modules\Product\API\Manager\Responses\Brand\BrandResponse;
use App\Modules\Product\Enums\Brand\BrandErrorEnum;
use App\Entities\BrandEntity;
use App\Services\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class BrandController extends BaseController
{
    #[OA\Post(path: '/brand/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['商品品牌模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: BrandQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: BrandQueryResponse::class))]
    public function query(BrandQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $request = $queryRequest->validated();

        try {
            $condition = [];

            $brandService = new BrandService();
            $result = $brandService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new BrandResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BrandErrorEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/brand/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['商品品牌模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: BrandCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: BrandResponse::class))]
    public function create(BrandCreateRequest $createRequest): JsonResponse
    {
        $request = $createRequest->validated();

        DB::beginTransaction();
        try {
            $input = new BrandEntity();
            $input->setData($request);

            $brandService = new BrandService();
            if ($brandService->save($input->toArray())) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(BrandErrorEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BrandErrorEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/brand/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['商品品牌模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: BrandResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $brandService = new BrandService();

            $brand = $brandService->getOneById($id);
            if (empty($brand)) {
                throw new CustomException(BrandErrorEnum::NOT_FOUND);
            }

            $response = new BrandResponse();
            $response->setData($brand);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BrandErrorEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/brand/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['商品品牌模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: BrandUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: BrandResponse::class))]
    public function update(BrandUpdateRequest $updateRequest): JsonResponse
    {
        $request = $updateRequest->validated();
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $brandService = new BrandService();

            $brand = $brandService->getOneById($id);
            if (empty($brand)) {
                throw new CustomException(BrandErrorEnum::NOT_FOUND);
            }

            $input = new BrandEntity();
            $input->setData($request);

            $brandService->updateById($input->toArray(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BrandErrorEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/brand/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['商品品牌模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: BrandDestroyResponse::class))]
    public function destroy(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $brandService = new BrandService();

            $brand = $brandService->getOneById($id);
            if (empty($brand)) {
                throw new CustomException(BrandErrorEnum::NOT_FOUND);
            }

            if ($brandService->removeById($id)) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(BrandErrorEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BrandErrorEnum::DESTROY_ERROR);
        }
    }
}
