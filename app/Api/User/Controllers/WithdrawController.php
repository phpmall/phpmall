<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Withdraw\WithdrawIndexRequest;
use App\Api\User\Requests\Withdraw\WithdrawStoreRequest;
use App\Api\User\Responses\Withdraw\WithdrawListResponse;
use App\Api\User\Responses\Withdraw\WithdrawResponse;
use App\Exceptions\NotImplementedException;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class WithdrawController extends BaseController
{
    #[OA\Get(path: '/withdraws', security: [['bearerAuth' => []]], summary: 'Withdraw Controller index', tags: ['会员中心'])]
    #[OA\Parameter(name: 'status', in: 'query', description: '提现状态', schema: new OA\Schema(type: 'integer', nullable: true))]
    #[OA\Parameter(name: 'page', in: 'query', description: '页码', schema: new OA\Schema(type: 'integer', example: 1))]
    #[OA\Parameter(name: 'per_page', in: 'query', description: '每页数量', schema: new OA\Schema(type: 'integer', example: 20))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WithdrawListResponse::class))]
    public function index(WithdrawIndexRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Post(path: '/withdraws', security: [['bearerAuth' => []]], summary: 'Withdraw Controller store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WithdrawStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WithdrawResponse::class))]
    public function store(WithdrawStoreRequest $request): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }

    #[OA\Get(path: '/withdraws/{id}', security: [['bearerAuth' => []]], summary: 'Withdraw Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WithdrawResponse::class))]
    public function show(int $id): JsonResponse
    {
        throw new NotImplementedException('TODO: implement '.__CLASS__.'::'.__FUNCTION__);
    }
}
