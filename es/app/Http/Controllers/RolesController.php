<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\roles\RolesCreateRequest;
use App\Http\Requests\roles\RolesQueryRequest;
use App\Http\Requests\roles\RolesUpdateRequest;
use App\Http\Responses\roles\RolesQueryResponse;
use App\Http\Responses\roles\RolesResponse;
use App\Entities\RolesEntity;
use App\Exception\CustomException;
use App\Services\RolesService;
use Illuminate\Http\Response;
use Illuminate\Support\Facade\DB;
use Illuminate\Support\Facade\Log;
use OpenApi\Attributes as OA;
use Throwable;

class RolesController extends Controller
{
    #[OA\Post(path: '/api/roles/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolesQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RolesQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->requestBody();
            $page = intval($this->input('page', 1));
            $pageSize = intval($this->input('pageSize', 10));

            $v = new RolesQueryRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $rolesService = new RolesService();
            $result = $rolesService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new RolesResponse();
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

    #[OA\Post(path: '/api/roles/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolesCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new RolesCreateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $input = new RolesInput();
            $input->setData($request);

            $rolesService = new RolesService();
            $insertId = $rolesService->save($input->toArray());
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

    #[OA\Get(path: '/api/roles/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: RolesResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->input('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $rolesService = new RolesService();
            $roles = $rolesService->getOne($condition);

            if (empty($roles)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new RolesResponse();
            $response->setData($roles);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('获取详情错误');
        }
    }

    #[OA\Put(path: '/api/roles/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: RolesUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new RolesUpdateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $rolesService = new RolesService();
            $roles = $rolesService->getById($request['id']);
            if (empty($roles)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $input = new RolesInput();
            $input->setData($request);

            $rolesService->update($input->toArray(), [
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

    #[OA\Delete(path: '/api/roles/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
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

            $rolesService = new RolesService();
            if ($rolesService->remove($condition)) {
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