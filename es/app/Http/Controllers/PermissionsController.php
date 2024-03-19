<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\permissions\PermissionsCreateRequest;
use App\Http\Requests\permissions\PermissionsQueryRequest;
use App\Http\Requests\permissions\PermissionsUpdateRequest;
use App\Http\Responses\permissions\PermissionsQueryResponse;
use App\Http\Responses\permissions\PermissionsResponse;
use App\Entities\PermissionsEntity;
use App\Exception\CustomException;
use App\Services\PermissionsService;
use Illuminate\Http\Response;
use Illuminate\Support\Facade\DB;
use Illuminate\Support\Facade\Log;
use OpenApi\Attributes as OA;
use Throwable;

class PermissionsController extends Controller
{
    #[OA\Post(path: '/api/permissions/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PermissionsQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->requestBody();
            $page = intval($this->input('page', 1));
            $pageSize = intval($this->input('pageSize', 10));

            $v = new PermissionsQueryRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $permissionsService = new PermissionsService();
            $result = $permissionsService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new PermissionsResponse();
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

    #[OA\Post(path: '/api/permissions/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new PermissionsCreateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $input = new PermissionsInput();
            $input->setData($request);

            $permissionsService = new PermissionsService();
            $insertId = $permissionsService->save($input->toArray());
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

    #[OA\Get(path: '/api/permissions/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: PermissionsResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->input('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $permissionsService = new PermissionsService();
            $permissions = $permissionsService->getOne($condition);

            if (empty($permissions)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new PermissionsResponse();
            $response->setData($permissions);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('获取详情错误');
        }
    }

    #[OA\Put(path: '/api/permissions/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: PermissionsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new PermissionsUpdateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $permissionsService = new PermissionsService();
            $permissions = $permissionsService->getById($request['id']);
            if (empty($permissions)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $input = new PermissionsInput();
            $input->setData($request);

            $permissionsService->update($input->toArray(), [
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

    #[OA\Delete(path: '/api/permissions/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['权限资源模块'])]
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

            $permissionsService = new PermissionsService();
            if ($permissionsService->remove($condition)) {
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