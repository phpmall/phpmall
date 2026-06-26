<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\UserBind\UserBindIndexRequest;
use App\Api\User\Requests\UserBind\UserBindRequest;
use App\Api\User\Requests\UserBind\UserUnbindRequest;
use App\Api\User\Responses\UserBind\UserBindListResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UserBindController extends BaseController
{
    #[OA\Get(path: '/binds', security: [['bearerAuth' => []]], summary: 'User Bind Controller index', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBindListResponse::class))]
    public function index(UserBindIndexRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/binds/bind', security: [['bearerAuth' => []]], summary: 'User Bind Controller bind', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserBindRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBindListResponse::class))]
    public function bind(UserBindRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/binds/unbind', security: [['bearerAuth' => []]], summary: 'User Bind Controller unbind', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UserUnbindRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserBindListResponse::class))]
    public function unbind(UserUnbindRequest $request): JsonResponse
    {
        return $this->success();
    }
}
