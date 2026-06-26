<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class SecurityController extends BaseController
{
    #[OA\Put(path: '/security/password', security: [['bearerAuth' => []]], summary: 'Security Controller update Password', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function updatePassword(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/security/phone', security: [['bearerAuth' => []]], summary: 'Security Controller update Phone', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function updatePhone(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/security/email', security: [['bearerAuth' => []]], summary: 'Security Controller update Email', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function updateEmail(Request $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/security/real-name', security: [['bearerAuth' => []]], summary: 'Security Controller real Name', tags: ['会员中心'])]
    #[OA\Response(response: 200, description: 'OK')]
    public function realName(Request $request): JsonResponse
    {
        return $this->success();
    }
}
