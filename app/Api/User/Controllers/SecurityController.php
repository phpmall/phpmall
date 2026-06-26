<?php

declare(strict_types=1);

namespace App\Api\User\Controllers;

use App\Api\User\Requests\Security\SecurityRealNameRequest;
use App\Api\User\Requests\Security\SecurityUpdateEmailRequest;
use App\Api\User\Requests\Security\SecurityUpdatePasswordRequest;
use App\Api\User\Requests\Security\SecurityUpdatePhoneRequest;
use App\Api\User\Responses\Security\SecurityUpdateResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class SecurityController extends BaseController
{
    #[OA\Put(path: '/security/password', security: [['bearerAuth' => []]], summary: 'Security Controller update Password', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SecurityUpdatePasswordRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SecurityUpdateResponse::class))]
    public function updatePassword(SecurityUpdatePasswordRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/security/phone', security: [['bearerAuth' => []]], summary: 'Security Controller update Phone', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SecurityUpdatePhoneRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SecurityUpdateResponse::class))]
    public function updatePhone(SecurityUpdatePhoneRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Put(path: '/security/email', security: [['bearerAuth' => []]], summary: 'Security Controller update Email', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SecurityUpdateEmailRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SecurityUpdateResponse::class))]
    public function updateEmail(SecurityUpdateEmailRequest $request): JsonResponse
    {
        return $this->success();
    }

    #[OA\Post(path: '/security/real-name', security: [['bearerAuth' => []]], summary: 'Security Controller real Name', tags: ['会员中心'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: SecurityRealNameRequest::class))]
    #[OA\Response(response: 200, description: 'OK', content: new OA\JsonContent(ref: SecurityUpdateResponse::class))]
    public function realName(SecurityRealNameRequest $request): JsonResponse
    {
        return $this->success();
    }
}
