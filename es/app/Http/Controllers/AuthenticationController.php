<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\authentication\AuthenticationCreateRequest;
use App\Http\Requests\authentication\AuthenticationQueryRequest;
use App\Http\Requests\authentication\AuthenticationUpdateRequest;
use App\Http\Responses\authentication\AuthenticationQueryResponse;
use App\Http\Responses\authentication\AuthenticationResponse;
use App\Entities\AuthenticationEntity;
use App\Services\AuthenticationService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Juling\Foundation\Exceptions\CustomException;
use OpenApi\Attributes as OA;
use Throwable;

class AuthenticationController extends Controller
{
    #[OA\Post(path: '/api/authentication/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户认证模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AuthenticationQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AuthenticationQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->requestBody();
            $page = intval($this->input('page', 1));
            $pageSize = intval($this->input('pageSize', 10));

            $v = new AuthenticationQueryRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $authenticationService = new AuthenticationService();
            $result = $authenticationService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AuthenticationResponse();
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

    #[OA\Post(path: '/api/authentication/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户认证模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AuthenticationCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new AuthenticationCreateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $input = new AuthenticationEntity;
            $input->setData($request);

            $authenticationService = new AuthenticationService();
            $insertId = $authenticationService->save($input->toArray());
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

    #[OA\Get(path: '/api/authentication/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户认证模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AuthenticationResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->input('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $authenticationService = new AuthenticationService();
            $authentication = $authenticationService->getOne($condition);

            if (empty($authentication)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new AuthenticationResponse();
            $response->setData($authentication);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('获取详情错误');
        }
    }

    #[OA\Put(path: '/api/authentication/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户认证模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AuthenticationUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new AuthenticationUpdateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $authenticationService = new AuthenticationService();
            $authentication = $authenticationService->getById($request['id']);
            if (empty($authentication)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $input = new AuthenticationEntity;
            $input->setData($request);

            $authenticationService->update($input->toArray(), [
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

    #[OA\Delete(path: '/api/authentication/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户认证模块'])]
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

            $authenticationService = new AuthenticationService();
            if ($authenticationService->remove($condition)) {
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
