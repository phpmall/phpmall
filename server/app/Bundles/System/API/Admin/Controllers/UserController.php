<?php

declare(strict_types=1);

namespace App\Bundles\System\API\Admin\Controllers;

use App\API\Admin\Controllers\BaseController;
use App\Bundles\System\API\Admin\Requests\User\UserCreateRequest;
use App\Bundles\System\API\Admin\Requests\User\UserQueryRequest;
use App\Bundles\System\API\Admin\Requests\User\UserUpdateRequest;
use App\Bundles\System\API\Admin\Responses\User\UserDestroyResponse;
use App\Bundles\System\API\Admin\Responses\User\UserQueryResponse;
use App\Bundles\System\API\Admin\Responses\User\UserResponse;
use App\Entities\UserEntity;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class UserController extends BaseController
{
    #[OA\Post(path: '/user/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserQueryResponse::class))]
    public function query(UserQueryRequest $queryRequest): JsonResponse
    {
        $page = intval($queryRequest->query('page', 1));
        $pageSize = intval($queryRequest->query('pageSize', 10));
        $request = $queryRequest->validated();

        try {
            $condition = [];

            $userService = new UserService();
            $result = $userService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserErrorEnum::QUERY_ERROR);
        }
    }

    #[OA\Post(path: '/user/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserResponse::class))]
    public function create(UserCreateRequest $createRequest): JsonResponse
    {
        $request = $createRequest->validated();

        DB::beginTransaction();
        try {
            $input = new UserEntity();
            $input->setData($request);

            $userService = new UserService();
            if ($userService->save($input->toArray())) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(UserErrorEnum::CREATE_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserErrorEnum::CREATE_ERROR);
        }
    }

    #[OA\Get(path: '/user/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserResponse::class))]
    public function show(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        try {
            $userService = new UserService();

            $user = $userService->getOneById($id);
            if (empty($user)) {
                throw new CustomException(UserErrorEnum::NOT_FOUND);
            }

            $response = new UserResponse();
            $response->setData($user);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserErrorEnum::SHOW_ERROR);
        }
    }

    #[OA\Put(path: '/user/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserResponse::class))]
    public function update(UserUpdateRequest $updateRequest): JsonResponse
    {
        $request = $updateRequest->validated();
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $userService = new UserService();

            $user = $userService->getOneById($id);
            if (empty($user)) {
                throw new CustomException(UserErrorEnum::NOT_FOUND);
            }

            $input = new UserEntity();
            $input->setData($request);

            $userService->updateById($input->toArray(), $id);

            DB::commit();

            return $this->success();
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserErrorEnum::UPDATE_ERROR);
        }
    }

    #[OA\Delete(path: '/user/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserDestroyResponse::class))]
    public function destroy(Request $request): JsonResponse
    {
        $id = intval($request->query('id', 0));

        DB::beginTransaction();
        try {
            $userService = new UserService();

            $user = $userService->getOneById($id);
            if (empty($user)) {
                throw new CustomException(UserErrorEnum::NOT_FOUND);
            }

            if ($userService->removeById($id)) {
                DB::commit();

                return $this->success();
            }

            throw new CustomException(UserErrorEnum::DESTROY_FAIL);
        } catch (Throwable $e) {
            DB::rollBack();

            if ($e instanceof CustomException) {
                return $this->error($e);
            }

            Log::error($e);

            return $this->error(UserErrorEnum::DESTROY_ERROR);
        }
    }
}
