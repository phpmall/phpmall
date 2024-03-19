<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\rolePermissions\RolePermissionsCreateRequest;
use App\Http\Requests\rolePermissions\RolePermissionsQueryRequest;
use App\Http\Requests\rolePermissions\RolePermissionsUpdateRequest;
use App\Http\Responses\rolePermissions\RolePermissionsQueryResponse;
use App\Http\Responses\rolePermissions\RolePermissionsResponse;
use App\Entities\RolePermissionsEntity;
use App\Exception\CustomException;
use App\Services\RolePermissionsService;
use Illuminate\Http\Response;
use Illuminate\Support\Facade\DB;
use Illuminate\Support\Facade\Log;
use OpenApi\Attributes as OA;
use Throwable;

class RolePermissionsController extends Controller
{
    #[OA\Post(path: '/api/rolePermissions/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['角色资源权限模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolePermissionsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RolePermissionsQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->requestBody();
            $page = intval($this->input('page', 1));
            $pageSize = intval($this->input('pageSize', 10));

            $v = new RolePermissionsQueryRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $rolePermissionsService = new RolePermissionsService();
            $result = $rolePermissionsService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new RolePermissionsResponse();
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

    #[OA\Post(path: '/api/rolePermissions/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['角色资源权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolePermissionsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new RolePermissionsCreateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $input = new RolePermissionsInput();
            $input->setData($request);

            $rolePermissionsService = new RolePermissionsService();
            $insertId = $rolePermissionsService->save($input->toArray());
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

    #[OA\Get(path: '/api/rolePermissions/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['角色资源权限模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RolePermissionsResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->input('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $rolePermissionsService = new RolePermissionsService();
            $rolePermissions = $rolePermissionsService->getOne($condition);

            if (empty($rolePermissions)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new RolePermissionsResponse();
            $response->setData($rolePermissions);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('获取详情错误');
        }
    }

    #[OA\Put(path: '/api/rolePermissions/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['角色资源权限模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolePermissionsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new RolePermissionsUpdateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $rolePermissionsService = new RolePermissionsService();
            $rolePermissions = $rolePermissionsService->getById($request['id']);
            if (empty($rolePermissions)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $input = new RolePermissionsInput();
            $input->setData($request);

            $rolePermissionsService->update($input->toArray(), [
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

    #[OA\Delete(path: '/api/rolePermissions/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['角色资源权限模块'])]
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

            $rolePermissionsService = new RolePermissionsService();
            if ($rolePermissionsService->remove($condition)) {
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