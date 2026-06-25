<?php

namespace App\Api\User\Controllers;

use App\Api\User\Requests\UpdateProfileRequest;
use App\Modules\User\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        $user = $this->resolveUser($request);

        return response()->json([
            'code' => 0,
            'data' => $user->load('addresses'),
        ]);
    }

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
