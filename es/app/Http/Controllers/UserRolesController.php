<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\userRoles\UserRolesCreateRequest;
use App\Http\Requests\userRoles\UserRolesQueryRequest;
use App\Http\Requests\userRoles\UserRolesUpdateRequest;
use App\Http\Responses\userRoles\UserRolesQueryResponse;
use App\Http\Responses\userRoles\UserRolesResponse;
use App\Entities\UserRolesEntity;
use App\Exception\CustomException;
use App\Services\UserRolesService;
use Illuminate\Http\Response;
use Illuminate\Support\Facade\DB;
use Illuminate\Support\Facade\Log;
use OpenApi\Attributes as OA;
use Throwable;

class UserRolesController extends Controller
{
    #[OA\Post(path: '/api/userRoles/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRolesQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserRolesQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->requestBody();
            $page = intval($this->input('page', 1));
            $pageSize = intval($this->input('pageSize', 10));

            $v = new UserRolesQueryRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $userRolesService = new UserRolesService();
            $result = $userRolesService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new UserRolesResponse();
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

    #[OA\Post(path: '/api/userRoles/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRolesCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new UserRolesCreateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $input = new UserRolesInput();
            $input->setData($request);

            $userRolesService = new UserRolesService();
            $insertId = $userRolesService->save($input->toArray());
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

    #[OA\Get(path: '/api/userRoles/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserRolesResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->input('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $userRolesService = new UserRolesService();
            $userRoles = $userRolesService->getOne($condition);

            if (empty($userRoles)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new UserRolesResponse();
            $response->setData($userRoles);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('获取详情错误');
        }
    }

    #[OA\Put(path: '/api/userRoles/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserRolesUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new UserRolesUpdateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $userRolesService = new UserRolesService();
            $userRoles = $userRolesService->getById($request['id']);
            if (empty($userRoles)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $input = new UserRolesInput();
            $input->setData($request);

            $userRolesService->update($input->toArray(), [
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

    #[OA\Delete(path: '/api/userRoles/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户角色模块'])]
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

            $userRolesService = new UserRolesService();
            if ($userRolesService->remove($condition)) {
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