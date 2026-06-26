<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\UpdateProfileRequest;
use App\Api\User\Responses\UserProfileResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class ProfileController extends BaseController
{
    #[OA\Get(path: '/profile', summary: '获取会员资料', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserProfileResponse::class))]
    public function index(): JsonResponse
    {
        $user = Auth::user();

        return response()->json([
            'code' => 0,
            'data' => $user,
        ]);
    }

    #[OA\Put(path: '/profile', summary: '更新会员资料', security: [['bearerAuth' => []]], tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: UpdateProfileRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: UserProfileResponse::class))]
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        return response()->json([
            'code' => 0,
            'message' => '更新成功',
            'data' => $user->fresh(),
        ]);
    }
}
