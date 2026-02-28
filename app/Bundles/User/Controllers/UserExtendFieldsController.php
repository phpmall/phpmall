<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers;

use App\Api\Admin\Controllers\BaseController;
use App\Bundles\User\Entities\UserExtendFieldsEntity;
use App\Bundles\User\Requests\UserExtendFields\UserExtendFieldsCreateRequest;
use App\Bundles\User\Requests\UserExtendFields\UserExtendFieldsDestroyRequest;
use App\Bundles\User\Requests\UserExtendFields\UserExtendFieldsQueryRequest;
use App\Bundles\User\Requests\UserExtendFields\UserExtendFieldsUpdateRequest;
use App\Bundles\User\Responses\UserExtendFields\UserExtendFieldsDestroyResponse;
use App\Bundles\User\Responses\UserExtendFields\UserExtendFieldsQueryResponse;
use App\Bundles\User\Responses\UserExtendFields\UserExtendFieldsResponse;
use App\Bundles\User\Services\UserExtendFieldsBundleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Enums\BusinessEnum;
use Juling\Foundation\Exceptions\BusinessException;
use OpenApi\Attributes as OA;
use Throwable;

class UserExtendFieldsController extends BaseController
{
    #[OA\Post(path: '/userExtendFields/query', summary: '查询用户扩展字段列表接口', security: [['bearerAuth' => []]], tags: ['用户扩展字段模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserExtendFieldsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserExtendFieldsQueryResponse::class))]
    public function query(UserExtendFieldsQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $requestData = $queryRequest->post();

        try {
            $condition = [];
            if (isset($requestData[UserExtendFieldsQueryRequest::getId])) {
                $condition[] = [UserExtendFieldsEntity::getId, '=', $requestData[UserExtendFieldsQueryRequest::getId]];
            }

            $userExtendFieldsBundleService = new UserExtendFieldsBundleService;
            $result = $userExtendFieldsBundleService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserExtendFieldsResponse($item);
                $result['data'][$key] = $response->toArray();
            }

            $response = new UserExtendFieldsQueryResponse($result);
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

    #[OA\Post(path: '/userExtendFields/store', summary: '新增用户扩展字段接口', security: [['bearerAuth' => []]], tags: ['用户扩展字段模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserExtendFieldsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserExtendFieldsResponse::class))]
    public function store(UserExtendFieldsCreateRequest $createRequest): JsonResponse
    {
        $requestData = $createRequest->post();

        DB::beginTransaction();
        try {
            $input = new UserExtendFieldsEntity($requestData);

            $userExtendFieldsBundleService = new UserExtendFieldsBundleService;
            if ($userExtendFieldsBundleService->save($input->toEntity())) {
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

    #[OA\Get(path: '/userExtendFields/show', summary: '获取用户扩展字段详情接口', security: [['bearerAuth' => []]], tags: ['用户扩展字段模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserExtendFieldsResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userExtendFieldsBundleService = new UserExtendFieldsBundleService;
            $userExtendFields = $userExtendFieldsBundleService->getOneById($id);
            if (empty($userExtendFields)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $response = new UserExtendFieldsResponse($userExtendFields);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof BusinessException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(BusinessEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/userExtendFields/update', summary: '更新用户扩展字段接口', security: [['bearerAuth' => []]], tags: ['用户扩展字段模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserExtendFieldsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserExtendFieldsResponse::class))]
    public function update(UserExtendFieldsUpdateRequest $updateRequest): JsonResponse
    {
        $id = intval($updateRequest->query('id', 0));
        $requestData = $updateRequest->post();

        DB::beginTransaction();
        try {
            $userExtendFieldsBundleService = new UserExtendFieldsBundleService;
            $userExtendFields = $userExtendFieldsBundleService->getOneById($id);
            if (empty($userExtendFields)) {
                throw new BusinessException(BusinessEnum::NOT_FOUND);
            }

            $input = new UserExtendFieldsEntity($requestData);

            $userExtendFieldsBundleService->updateById($input->toEntity(), $id);

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

    #[OA\Delete(path: '/userExtendFields/destroy', summary: '删除用户扩展字段接口', security: [['bearerAuth' => []]], tags: ['用户扩展字段模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserExtendFieldsDestroyRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserExtendFieldsDestroyResponse::class))]
    public function destroy(UserExtendFieldsDestroyRequest $destroyRequest): JsonResponse
    {
        $requestData = $destroyRequest->post();

        DB::beginTransaction();
        try {
            $userExtendFieldsBundleService = new UserExtendFieldsBundleService;
            if ($userExtendFieldsBundleService->removeByIds($requestData['ids'])) {
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
