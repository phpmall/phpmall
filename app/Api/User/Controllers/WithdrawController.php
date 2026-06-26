<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Withdraw\WithdrawStoreRequest;
use App\Api\User\Responses\Withdraw\WithdrawListResponse;
use App\Api\User\Responses\Withdraw\WithdrawResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class WithdrawController extends BaseController
{
    #[OA\Get(path: '/withdraws', security: [['bearerAuth' => []]], summary: 'Withdraw Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WithdrawListResponse::class))]
    public function index(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/withdraws', security: [['bearerAuth' => []]], summary: 'Withdraw Controller store', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: WithdrawStoreRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WithdrawResponse::class))]
    public function store(WithdrawStoreRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Get(path: '/withdraws/{id}', security: [['bearerAuth' => []]], summary: 'Withdraw Controller show', tags: ['会员中心'])]
    #[OA\Parameter(name: 'id', description: 'ID', in: 'path', required: true)]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: WithdrawResponse::class))]
    public function show(int $id): JsonResponse
    {
        return $this->success();
    }
}
