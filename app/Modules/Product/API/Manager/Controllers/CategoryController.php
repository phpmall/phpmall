<?php

declare(strict_types=1);

namespace App\Modules\Product\API\Manager\Controllers;

use App\API\Manager\Controllers\BaseController;
use App\Modules\Product\API\Manager\Requests\Category\CategoryCreateRequest;
use App\Modules\Product\API\Manager\Requests\Category\CategoryQueryRequest;
use App\Modules\Product\API\Manager\Requests\Category\CategoryUpdateRequest;
use App\Modules\Product\API\Manager\Responses\Category\CategoryDestroyResponse;
use App\Modules\Product\API\Manager\Responses\Category\CategoryQueryResponse;
use App\Modules\Product\API\Manager\Responses\Category\CategoryResponse;
use App\Modules\Product\Enums\Category\CategoryErrorEnum;
use App\Entities\CategoryEntity;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class CategoryController extends BaseController
{
    #[OA\Post(path: '/category/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CategoryQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryQueryResponse::class))]
    public function query(CategoryQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $request = $queryRequest->validated();

        try {
            $condition = [];

            $categoryService = new CategoryService();
            $result = $categoryService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new CategoryResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(CategoryErrorEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/category/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CategoryCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryResponse::class))]
    public function create(CategoryCreateRequest $createRequest): JsonResponse
    {
        $request = $createRequest->validated();

        DB::beginTransaction();
        try {
            $input = new CategoryEntity();
            $input->setData($request);

            $categoryService = new CategoryService();
            if ($categoryService->save($input->toArray())) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(CategoryErrorEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(CategoryErrorEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/category/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $categoryService = new CategoryService();

            $category = $categoryService->getOneById($id);
            if (empty($category)) {
                throw new CustomException(CategoryErrorEnum::NOT_FOUND);
            }

            $response = new CategoryResponse();
            $response->setData($category);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(CategoryErrorEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/category/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: CategoryUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryResponse::class))]
    public function update(CategoryUpdateRequest $updateRequest): JsonResponse
    {
        $request = $updateRequest->validated();
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $categoryService = new CategoryService();

            $category = $categoryService->getOneById($id);
            if (empty($category)) {
                throw new CustomException(CategoryErrorEnum::NOT_FOUND);
            }

            $input = new CategoryEntity();
            $input->setData($request);

            $categoryService->updateById($input->toArray(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(CategoryErrorEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/category/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['商品分类模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: CategoryDestroyResponse::class))]
    public function destroy(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $categoryService = new CategoryService();

            $category = $categoryService->getOneById($id);
            if (empty($category)) {
                throw new CustomException(CategoryErrorEnum::NOT_FOUND);
            }

            if ($categoryService->removeById($id)) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(CategoryErrorEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(CategoryErrorEnum::DESTROY_ERROR);
        }
    }
}
