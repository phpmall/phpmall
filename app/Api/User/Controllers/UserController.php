<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\UpdateProfileRequest;
use App\Api\User\Responses\UserProfileResponse;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class UserController extends BaseController
{
    #[OA\Get(path: '/me', summary: '获取会员资料', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserProfileResponse::class))]
    public function profile(Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        return response()->json([
            'code' => 0,
            'data' => $user->load('addresses'),
        ]);
    }

    #[OA\Put(path: '/me', summary: '更新会员资料', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UpdateProfileRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserProfileResponse::class))]
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = $this->resolveUser($request);
        $user->update($request->validated());

        return response()->json([
            'code' => 0,
            'message' => '更新成功',
            'data' => $user->fresh(),
        ]);
    }

    private function resolveUser(Request $request): User
    {
        $user = $request->user();

        if (! $user instanceof User) {
            abort(401, '未登录');
        }

        return $user;
    }
}
