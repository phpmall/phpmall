<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\permission\PermissionCreateRequest;
use App\Http\Requests\permission\PermissionQueryRequest;
use App\Http\Requests\permission\PermissionUpdateRequest;
use App\Http\Responses\permission\PermissionQueryResponse;
use App\Http\Responses\permission\PermissionResponse;
use App\Entities\PermissionEntity;
use App\Services\PermissionService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class PermissionController extends Controller
{
    #[OA\Post(path: '/api/permission/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PermissionQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->requestBody();
            $page = intval($this->input('page', 1));
            $pageSize = intval($this->input('pageSize', 10));

            $v = new PermissionQueryRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $permissionService = new PermissionService();
            $result = $permissionService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new PermissionResponse();
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

    #[OA\Post(path: '/api/permission/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new PermissionCreateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $input = new PermissionEntity;
            $input->setData($request);

            $permissionService = new PermissionService();
            $insertId = $permissionService->save($input->toArray());
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

    #[OA\Get(path: '/api/permission/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PermissionResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->input('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $permissionService = new PermissionService();
            $permission = $permissionService->getOne($condition);

            if (empty($permission)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new PermissionResponse();
            $response->setData($permission);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('获取详情错误');
        }
    }

    #[OA\Put(path: '/api/permission/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new PermissionUpdateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $permissionService = new PermissionService();
            $permission = $permissionService->getById($request['id']);
            if (empty($permission)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $input = new PermissionEntity;
            $input->setData($request);

            $permissionService->update($input->toArray(), [
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

    #[OA\Delete(path: '/api/permission/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
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

            $permissionService = new PermissionService();
            if ($permissionService->remove($condition)) {
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
