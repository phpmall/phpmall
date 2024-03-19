<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\userPermission\UserPermissionCreateRequest;
use App\Http\Requests\userPermission\UserPermissionQueryRequest;
use App\Http\Requests\userPermission\UserPermissionUpdateRequest;
use App\Http\Responses\userPermission\UserPermissionQueryResponse;
use App\Http\Responses\userPermission\UserPermissionResponse;
use App\Entities\UserPermissionEntity;
use App\Services\UserPermissionService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class UserPermissionController extends Controller
{
    #[OA\Post(path: '/api/userPermission/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户资源权限模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserPermissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserPermissionQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->requestBody();
            $page = intval($this->input('page', 1));
            $pageSize = intval($this->input('pageSize', 10));

            $v = new UserPermissionQueryRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $userPermissionService = new UserPermissionService();
            $result = $userPermissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserPermissionResponse();
                $response->setData($item);
                $result['data'][$key] = $response->toArray();
            }

            return $this->success($result);
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('查询列表错误');
        }
    }

    #[OA\Post(path: '/api/userPermission/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户资源权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserPermissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new UserPermissionCreateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $input = new UserPermissionEntity;
            $input->setData($request);

            $userPermissionService = new UserPermissionService();
            $insertId = $userPermissionService->save($input->toArray());
            if ($insertId > 0) {
                DB::commit();

                return $this->success('新增数据成功');
            }

            throw new CustomException('新增数据失败');
        } catch (Throwable $e) {
            DB::rollback();

            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('新增数据错误');
        }
    }

    #[OA\Get(path: '/api/userPermission/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户资源权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserPermissionResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->input('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $userPermissionService = new UserPermissionService();
            $userPermission = $userPermissionService->getOne($condition);

            if (empty($userPermission)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new UserPermissionResponse();
            $response->setData($userPermission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('获取详情错误');
        }
    }

    #[OA\Put(path: '/api/userPermission/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户资源权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserPermissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new UserPermissionUpdateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userPermissionService = new UserPermissionService();
            $userPermission = $userPermissionService->getById($request['id']);
            if (empty($userPermission)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $input = new UserPermissionEntity;
            $input->setData($request);

            $userPermissionService->update($input->toArray(), [
                ['id', '=', $request['id']],
            ]);

            DB::commit();

            return $this->success('更新数据成功');
        } catch (Throwable $e) {
            DB::rollback();

            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('更新数据错误');
        }
    }

    #[OA\Delete(path: '/api/userPermission/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户资源权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK')]
    public function destroy(): Response
    {
        DB::startTrans();
        try {
            $id = intval($this->input('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $userPermissionService = new UserPermissionService();
            if ($userPermissionService->remove($condition)) {
                DB::commit();

                return $this->success('删除数据成功');
            }

            throw new CustomException('删除数据失败');
        } catch (Throwable $e) {
            DB::rollback();

            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('删除数据错误');
        }
    }
}
