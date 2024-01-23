<?php

declare(strict_types=1);

namespace App\Bundles\User\Controllers\User;

use App\Api\User\Controllers\BaseController;
use App\Bundles\User\Requests\ProfileRequest;
use App\Bundles\User\Responses\ProfileResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class ProfileController extends BaseController
{
    #[OA\Get(path: '/profile/show', summary: '获取用户资料', security: [['bearerAuth' => []]], tags: ['用户中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: ProfileResponse::class))]
    public function show(Request $request): JsonResponse
    {
        return $this->success(['test user token', $request->user()]);
    }

    #[OA\Put(path: '/profile/update', summary: '更新用户资料', security: [['bearerAuth' => []]], tags: ['用户中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ProfileRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(ProfileRequest $request): JsonResponse
    {
        return $this->success(['update user token', $request->user()]);
    }
}
