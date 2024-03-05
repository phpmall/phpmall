<?php

declare(strict_types=1);

namespace App\Api\Auth\Controllers;

use App\Http\Controllers\Auth\Requests\Forget\ForgetMobileRequest;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ForgetController extends BaseController
{
    #[OA\Post(path: '/forget/mobile', summary: '发送手机短信验证码', tags: ['忘记密码'])]
    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: ForgetMobileRequest::class))]
    #[OA\Response(response: 200, description: 'OK')]
    public function mobile(ForgetMobileRequest $request): JsonResponse
    {
        return $this->success(['mobile']);
    }
}
