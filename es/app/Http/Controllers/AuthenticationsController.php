<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\authentications\AuthenticationsCreateRequest;
use App\Http\Requests\authentications\AuthenticationsQueryRequest;
use App\Http\Requests\authentications\AuthenticationsUpdateRequest;
use App\Http\Responses\authentications\AuthenticationsQueryResponse;
use App\Http\Responses\authentications\AuthenticationsResponse;
use App\Entities\AuthenticationsEntity;
use App\Exception\CustomException;
use App\Services\AuthenticationsService;
use Illuminate\Http\Response;
use Illuminate\Support\Facade\DB;
use Illuminate\Support\Facade\Log;
use OpenApi\Attributes as OA;
use Throwable;

class AuthenticationsController extends Controller
{
    #[OA\Post(path: '/api/authentications/query', summary: '查询列表接口', security: [['bearerAuth' => []]], tags: ['用户认证模块'])]
    #[OA\Parameter(name: 'page', description: '当前页码', in: 'query', required: true, example: 1)]
    #[OA\Parameter(name: 'pageSize', description: '每页分页数', in: 'query', required: false, example: 10)]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AuthenticationsQueryRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AuthenticationsQueryResponse::class))]
    public function query(): Response
    {
        try {
            $request = $this->requestBody();
            $page = intval($this->input('page', 1));
            $pageSize = intval($this->input('pageSize', 10));

            $v = new AuthenticationsQueryRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $condition = [
                //
            ];

            $authenticationsService = new AuthenticationsService();
            $result = $authenticationsService->page($condition, $page, $pageSize);

            foreach ($result['data'] as $key => $item) {
                $response = new AuthenticationsResponse();
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

    #[OA\Post(path: '/api/authentications/create', summary: '新增接口', security: [['bearerAuth' => []]], tags: ['用户认证模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AuthenticationsCreateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function create(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new AuthenticationsCreateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $input = new AuthenticationsInput();
            $input->setData($request);

            $authenticationsService = new AuthenticationsService();
            $insertId = $authenticationsService->save($input->toArray());
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

    #[OA\Get(path: '/api/authentications/show', summary: '获取详情接口', security: [['bearerAuth' => []]], tags: ['用户认证模块'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'query', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: AuthenticationsResponse::class))]
    public function show(): Response
    {
        try {
            $id = intval($this->input('id', 0));

            $condition = [
                ['id', '=', $id],
            ];

            $authenticationsService = new AuthenticationsService();
            $authentications = $authenticationsService->getOne($condition);

            if (empty($authentications)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $response = new AuthenticationsResponse();
            $response->setData($authentications);

            return $this->success($response->toArray());
        } catch (Throwable $e) {
            if ($e instanceof CustomException) {
                return $this->fail($e->getMessage());
            }

            Log::error($e);

            return $this->fail('获取详情错误');
        }
    }

    #[OA\Put(path: '/api/authentications/update', summary: '更新接口', security: [['bearerAuth' => []]], tags: ['用户认证模块'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: AuthenticationsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(): Response
    {
        DB::startTrans();
        try {
            $request = $this->requestBody();

            $v = new AuthenticationsUpdateRequest();
            if (! $v->check($request)) {
                throw new CustomException($v->getError());
            }

            $authenticationsService = new AuthenticationsService();
            $authentications = $authenticationsService->getById($request['id']);
            if (empty($authentications)) {
                throw new CustomException('数据不存在或状态异常');
            }

            $input = new AuthenticationsInput();
            $input->setData($request);

            $authenticationsService->update($input->toArray(), [
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

    #[OA\Delete(path: '/api/authentications/destroy', summary: '删除接口', security: [['bearerAuth' => []]], tags: ['用户认证模块'])]
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

            $authenticationsService = new AuthenticationsService();
            if ($authenticationsService->remove($condition)) {
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