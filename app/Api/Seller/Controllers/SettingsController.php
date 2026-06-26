<?php

declare(strict_types=1);

namespace App\Api\Seller\Controllers;

use App\Api\Seller\Requests\Settings\SettingsUpdatePasswordRequest;
use App\Api\Seller\Requests\Settings\SettingsUpdateRequest;
use App\Api\Seller\Responses\Settings\SettingsResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SettingsController extends BaseController
{
    #[OA\Get(path: '/settings', summary: '获取设置', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SettingsResponse::class))]
    public function index(): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/settings', summary: '更新设置', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SettingsUpdateRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function update(SettingsUpdateRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/settings/password', summary: '修改密码', security: [['bearerAuth' => []]], tags: ['商家中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SettingsUpdatePasswordRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function updatePassword(SettingsUpdatePasswordRequest $request): JsonResponse
    {
        return $this->success();
    }
}
